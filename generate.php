<?php
// generate.php — Replaces the fake client-side Anthropic fetch. This calls Gemini
// SERVER-SIDE (your key never touches the browser), checks REAL credit balance before
// spending money, and only decrements credits after a genuinely successful generation.

session_start();
header('Content-Type: application/json');
require __DIR__ . '/db.php';
$config = require __DIR__ . '/config.php';
$conn = aura_db();

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Please sign in to generate a creation.']);
    exit;
}
$user_id = (int)$_SESSION['user_id'];

$data = json_decode(file_get_contents('php://input'), true);
$mode = trim($data['mode'] ?? 'Website');
$prompt = trim($data['prompt'] ?? '');
$is_edit = (bool)($data['is_edit'] ?? false);
$existing_html = trim($data['existing_html'] ?? '');
$project_id = (int)($data['project_id'] ?? 0);

if (strlen($prompt) < 5) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Please describe what you want in a bit more detail.']);
    exit;
}

// --- Real credit check BEFORE spending any money on the API call ---
$stmt = $conn->prepare("SELECT credits, name FROM aura_users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$is_edit && $user['credits'] <= 0) {
    http_response_code(402);
    echo json_encode(['success' => false, 'error' => 'You are out of credits. Please upgrade to continue.', 'needs_upgrade' => true]);
    exit;
}

// --- Real system prompts per mode (server-side, so they can't be tampered with from the browser) ---
$systemPrompts = [
    'Website' => "You are Aura Creator, an expert Indian web designer. Generate a COMPLETE single-page HTML website. Only inline CSS in a <style> tag. Google Fonts allowed via @import. MUST include: sticky nav with logo + CTA, full-width hero with headline + subtext + 2 CTAs (one WhatsApp), services grid (3-4 cards), testimonials section, footer with address + WhatsApp link. Visually bold, modern rounded cards, professional typography. Use the exact business described. Indian context, INR pricing. Return ONLY raw complete HTML — no markdown, no backticks, no explanation.",
    'App' => "You are Aura Creator, an expert mobile app UI designer. Generate a complete HTML page that looks like a real polished mobile app screen. Must have: top app bar, hero stat/balance card, feature grid, bottom navigation bar. Dark theme preferred. Realistic sample data. Only inline CSS. Return ONLY raw HTML.",
    'Design Studio' => "You are Aura Creator, an expert Indian social media designer. Generate an HTML page with 4-6 social media post mockups (Instagram/WhatsApp status) with vibrant gradients, bold type, marketing copy and price tags. Include a brand header. Only inline CSS. Return ONLY raw HTML.",
    'Video' => "You are Aura Creator, a viral content strategist. Generate an HTML page with a formatted short-form video script: Hook (0-3s), Scene 1, Scene 2, Result shot, CTA — each in a color-coded card with timing, voiceover text, visual direction, on-screen text. Include hashtags and caption. Only inline CSS. Return ONLY raw HTML.",
    'Launch Kit' => "You are Aura Creator, an Indian business strategist. Generate an HTML page: bold title header, positioning statement, INR pricing tiers, WhatsApp sales script, 30-day content calendar table, CRM follow-up sequence, 3 ad copy variations. Light background, colorful section cards. Only inline CSS. Return ONLY raw HTML.",
];

if ($is_edit) {
    $system = "You are Aura Creator. Apply this exact edit to the given HTML and return the COMPLETE updated HTML — no markdown, no backticks, no explanation.";
    $userMessage = "Current HTML:\n" . $existing_html . "\n\nEdit requested: " . $prompt;
} else {
    $system = $systemPrompts[$mode] ?? $systemPrompts['Website'];
    $userMessage = $prompt;
}

// --- Real call to Gemini (server-side, key never exposed) ---
$gemini_url = $config['gemini']['api_base'] . '/' . $config['gemini']['model'] . ':generateContent?key=' . $config['gemini']['api_key'];

$payload = json_encode([
    'contents' => [
        ['parts' => [['text' => $system . "\n\n" . $userMessage]]]
    ],
    'generationConfig' => ['temperature' => 0.8, 'maxOutputTokens' => 8192],
]);

$ch = curl_init($gemini_url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_TIMEOUT => 60,
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ($curl_error || $http_code !== 200) {
    error_log("Aura Creator generate.php Gemini call failed: HTTP $http_code | $curl_error | $response");
    http_response_code(502);
    echo json_encode(['success' => false, 'error' => 'Generation failed right now. Please try again.']);
    exit;
}

$result = json_decode($response, true);
$html = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

if (!$html) {
    error_log("Aura Creator: Gemini returned no content | $response");
    http_response_code(502);
    echo json_encode(['success' => false, 'error' => 'AI did not return usable content. Please try again with a more detailed description.']);
    exit;
}

// Strip any markdown fencing Gemini might add despite instructions
$html = preg_replace('/^```html\s*/i', '', trim($html));
$html = preg_replace('/^```\s*/', '', $html);
$html = preg_replace('/\s*```$/', '', $html);

if (strlen($html) < 60) {
    http_response_code(502);
    echo json_encode(['success' => false, 'error' => 'Generated content was too short. Please try again with more detail.']);
    exit;
}

// --- Real DB write — only now, after genuine success ---
if ($is_edit && $project_id) {
    $stmt = $conn->prepare("UPDATE aura_projects SET html_content = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
    $stmt->bind_param('sii', $html, $project_id, $user_id);
    $stmt->execute();
    $returned_project_id = $project_id;
} else {
    $project_name = mb_substr(trim($prompt), 0, 60);
    $stmt = $conn->prepare("INSERT INTO aura_projects (user_id, name, mode, prompt, html_content) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('issss', $user_id, $project_name, $mode, $prompt, $html);
    $stmt->execute();
    $returned_project_id = $stmt->insert_id;

    // Real credit deduction — only after a genuinely successful, saved generation
    $conn->query("UPDATE aura_users SET credits = credits - 1 WHERE id = $user_id AND credits > 0");
}

// Return the real, current credit balance so the frontend never has to guess
$stmt = $conn->prepare("SELECT credits FROM aura_users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$creditsNow = $stmt->get_result()->fetch_assoc()['credits'] ?? 0;

echo json_encode([
    'success' => true,
    'html' => $html,
    'project_id' => $returned_project_id,
    'credits' => (int)$creditsNow,
]);

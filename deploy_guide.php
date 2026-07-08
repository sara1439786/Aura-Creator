<?php
// deploy_guide.php — Real Gemini call for a personalized deploy guide.
// Replaces the fake Anthropic fetch that had no API key.

session_start();
header('Content-Type: application/json');
require __DIR__ . '/db.php';
$config = require __DIR__ . '/config.php';

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Please sign in first.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$platform = trim($data['platform'] ?? 'vercel');

$allowed = ['vercel', 'netlify', 'playstore', 'hostinger', 'github', 'whatsapp'];
if (!in_array($platform, $allowed)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Unknown platform.']);
    exit;
}

$system = "You are a friendly tech support guide. Give simple numbered steps to deploy a single HTML file. NO jargon. Write for a non-developer Indian business owner. Return valid HTML using <ol><li> tags only, no other tags, no markdown fencing. Each step max 2 sentences. Be specific and actionable.";
$userMessage = "How to deploy a single HTML file to {$platform}? Simple steps for a non-technical person.";

$gemini_url = $config['gemini']['api_base'] . '/' . $config['gemini']['model'] . ':generateContent?key=' . $config['gemini']['api_key'];

$payload = json_encode([
    'contents' => [['parts' => [['text' => $system . "\n\n" . $userMessage]]]],
    'generationConfig' => ['temperature' => 0.5, 'maxOutputTokens' => 800],
]);

$ch = curl_init($gemini_url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_TIMEOUT => 30,
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    error_log("Aura Creator deploy_guide failed: HTTP $http_code | $response");
    http_response_code(502);
    echo json_encode(['success' => false, 'error' => 'Could not generate guide right now.']);
    exit;
}

$result = json_decode($response, true);
$guide = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

if (!$guide) {
    http_response_code(502);
    echo json_encode(['success' => false, 'error' => 'AI did not return a usable guide.']);
    exit;
}

$guide = preg_replace('/^```html?\s*/i', '', trim($guide));
$guide = preg_replace('/\s*```$/', '', $guide);

echo json_encode(['success' => true, 'guide_html' => $guide]);

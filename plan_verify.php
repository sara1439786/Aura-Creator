<?php
// plan_verify.php — Real HMAC signature verification. Credits and plan are ONLY
// upgraded after this cryptographic check passes — never on the browser's word alone.

session_start();
header('Content-Type: application/json');
require __DIR__ . '/db.php';
$config = require __DIR__ . '/config.php';
$conn = aura_db();

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Please sign in first.']);
    exit;
}
$user_id = (int)$_SESSION['user_id'];

$data = json_decode(file_get_contents('php://input'), true);
$razorpay_order_id = trim($data['razorpay_order_id'] ?? '');
$razorpay_payment_id = trim($data['razorpay_payment_id'] ?? '');
$razorpay_signature = trim($data['razorpay_signature'] ?? '');

if (!$razorpay_order_id || !$razorpay_payment_id || !$razorpay_signature) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing payment verification fields.']);
    exit;
}

$stmt = $conn->prepare(
    "SELECT * FROM aura_plan_payments WHERE user_id = ? AND razorpay_order_id = ? AND status = 'created'"
);
$stmt->bind_param('is', $user_id, $razorpay_order_id);
$stmt->execute();
$payment = $stmt->get_result()->fetch_assoc();

if (!$payment) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Payment record not found.']);
    exit;
}

// --- Real signature verification, Razorpay's documented HMAC-SHA256 method ---
$generated_signature = hash_hmac(
    'sha256',
    $razorpay_order_id . '|' . $razorpay_payment_id,
    $config['razorpay']['key_secret']
);

if (!hash_equals($generated_signature, $razorpay_signature)) {
    error_log("Aura Creator: plan payment signature mismatch for user $user_id");
    $conn->query("UPDATE aura_plan_payments SET status = 'failed' WHERE id = {$payment['id']}");
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Payment could not be verified.']);
    exit;
}

// Signature genuinely valid — now actually upgrade the account
$stmt = $conn->prepare(
    "UPDATE aura_plan_payments SET status = 'paid', razorpay_payment_id = ?, razorpay_signature = ? WHERE id = ?"
);
$stmt->bind_param('ssi', $razorpay_payment_id, $razorpay_signature, $payment['id']);
$stmt->execute();

$plan = $config['plans'][$payment['plan_key']];
$stmt = $conn->prepare(
    "UPDATE aura_users SET plan = ?, credits = credits + ?, plan_expires_at = DATE_ADD(NOW(), INTERVAL 30 DAY) WHERE id = ?"
);
$stmt->bind_param('sii', $payment['plan_key'], $plan['credits'], $user_id);
$stmt->execute();

$stmt = $conn->prepare("SELECT plan, credits FROM aura_users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$updated = $stmt->get_result()->fetch_assoc();

echo json_encode([
    'success' => true,
    'plan' => $updated['plan'],
    'credits' => (int)$updated['credits'],
]);

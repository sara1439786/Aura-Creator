<?php
// download_verify.php — Real signature verification, then issues a real one-time
// download token. The token is what download_package.php checks before releasing files.

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

$stmt = $conn->prepare(
    "SELECT * FROM aura_download_payments WHERE user_id = ? AND razorpay_order_id = ? AND status = 'created'"
);
$stmt->bind_param('is', $user_id, $razorpay_order_id);
$stmt->execute();
$payment = $stmt->get_result()->fetch_assoc();

if (!$payment) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Payment record not found.']);
    exit;
}

$generated_signature = hash_hmac(
    'sha256',
    $razorpay_order_id . '|' . $razorpay_payment_id,
    $config['razorpay']['key_secret']
);

if (!hash_equals($generated_signature, $razorpay_signature)) {
    error_log("Aura Creator: download payment signature mismatch for user $user_id");
    $conn->query("UPDATE aura_download_payments SET status = 'failed' WHERE id = {$payment['id']}");
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Payment could not be verified.']);
    exit;
}

// Real one-time token — this is the only thing that unlocks download_package.php
$token = bin2hex(random_bytes(32));

$stmt = $conn->prepare(
    "UPDATE aura_download_payments SET status = 'paid', razorpay_payment_id = ?, razorpay_signature = ?, download_token = ? WHERE id = ?"
);
$stmt->bind_param('sssi', $razorpay_payment_id, $razorpay_signature, $token, $payment['id']);
$stmt->execute();

echo json_encode([
    'success' => true,
    'download_url' => 'download_package.php?token=' . $token,
]);

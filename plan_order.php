<?php
// plan_order.php — Creates a REAL Razorpay order for a plan upgrade.
// This replaces doUpgrade() which previously gave 20 free credits with zero payment.

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
$plan_key = trim($data['plan'] ?? '');

if (!isset($config['plans'][$plan_key])) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Invalid plan selected.']);
    exit;
}

$plan = $config['plans'][$plan_key];
$amount_paise = $plan['price'] * 100;

// --- Real call to Razorpay Orders API ---
$ch = curl_init('https://api.razorpay.com/v1/orders');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode([
        'amount' => $amount_paise,
        'currency' => $config['razorpay']['currency'],
        'receipt' => 'aura_plan_' . $user_id . '_' . time(),
        'notes' => ['user_id' => (string)$user_id, 'plan' => $plan_key],
    ]),
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_USERPWD => $config['razorpay']['key_id'] . ':' . $config['razorpay']['key_secret'],
    CURLOPT_TIMEOUT => 15,
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    error_log("Aura Creator plan_order failed: HTTP $http_code | $response");
    http_response_code(502);
    echo json_encode(['success' => false, 'error' => 'Could not start payment. Please try again.']);
    exit;
}

$order = json_decode($response, true);
$razorpay_order_id = $order['id'] ?? null;

if (!$razorpay_order_id) {
    http_response_code(502);
    echo json_encode(['success' => false, 'error' => 'Payment gateway did not return a valid order.']);
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO aura_plan_payments (user_id, plan_key, amount, razorpay_order_id, status) VALUES (?, ?, ?, ?, 'created')"
);
$stmt->bind_param('isds', $user_id, $plan_key, $plan['price'], $razorpay_order_id);
$stmt->execute();

echo json_encode([
    'success' => true,
    'razorpay_order_id' => $razorpay_order_id,
    'amount_paise' => $amount_paise,
    'razorpay_key_id' => $config['razorpay']['key_id'],
]);

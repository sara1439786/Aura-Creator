<?php
// download_order.php — Real Razorpay order for a one-time download purchase.

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
$project_id = (int)($data['project_id'] ?? 0);
$download_type = trim($data['download_type'] ?? '');

if (!isset($config['downloads'][$download_type])) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Invalid download type.']);
    exit;
}

// Confirm the project is real and actually belongs to this user
$stmt = $conn->prepare("SELECT id FROM aura_projects WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $project_id, $user_id);
$stmt->execute();
if (!$stmt->get_result()->fetch_assoc()) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Project not found.']);
    exit;
}

$download = $config['downloads'][$download_type];
$amount_paise = $download['price'] * 100;

$ch = curl_init('https://api.razorpay.com/v1/orders');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode([
        'amount' => $amount_paise,
        'currency' => $config['razorpay']['currency'],
        'receipt' => 'aura_dl_' . $user_id . '_' . time(),
        'notes' => ['user_id' => (string)$user_id, 'project_id' => (string)$project_id, 'type' => $download_type],
    ]),
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_USERPWD => $config['razorpay']['key_id'] . ':' . $config['razorpay']['key_secret'],
    CURLOPT_TIMEOUT => 15,
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    error_log("Aura Creator download_order failed: HTTP $http_code | $response");
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
    "INSERT INTO aura_download_payments (user_id, project_id, download_type, amount, razorpay_order_id, status)
     VALUES (?, ?, ?, ?, ?, 'created')"
);
$stmt->bind_param('iisds', $user_id, $project_id, $download_type, $download['price'], $razorpay_order_id);
$stmt->execute();

echo json_encode([
    'success' => true,
    'razorpay_order_id' => $razorpay_order_id,
    'amount_paise' => $amount_paise,
    'razorpay_key_id' => $config['razorpay']['key_id'],
]);

<?php
// signin.php — Real authentication. Verifies password_hash, sets a real server session.

session_start();
header('Content-Type: application/json');
require __DIR__ . '/db.php';
$conn = aura_db();

$data = json_decode(file_get_contents('php://input'), true);
$email = strtolower(trim($data['email'] ?? ''));
$password = $data['password'] ?? '';

if (!$email || !$password) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Please enter your email and password.']);
    exit;
}

$stmt = $conn->prepare("SELECT id, name, email, password_hash, plan, credits FROM aura_users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Real password verification — generic error either way, never reveal which field was wrong
if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Incorrect email or password.']);
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];

echo json_encode([
    'success' => true,
    'user' => [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'plan' => $user['plan'],
        'credits' => (int)$user['credits'],
    ],
]);

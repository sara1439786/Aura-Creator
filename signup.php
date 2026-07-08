<?php
// signup.php — Real account creation. Password is hashed, never stored plain.
// Session is set server-side via PHP session, not localStorage.

session_start();
header('Content-Type: application/json');
require __DIR__ . '/db.php';
$config = require __DIR__ . '/config.php';
$conn = aura_db();

$data = json_decode(file_get_contents('php://input'), true);
$name = trim($data['name'] ?? '');
$email = strtolower(trim($data['email'] ?? ''));
$password = $data['password'] ?? '';

if (strlen($name) < 2) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Please enter your full name.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Please enter a valid email address.']);
    exit;
}
if (strlen($password) < 8) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Password must be at least 8 characters.']);
    exit;
}

// Check if email is already taken — real check, not assumed
$stmt = $conn->prepare("SELECT id FROM aura_users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
if ($stmt->get_result()->fetch_assoc()) {
    http_response_code(409);
    echo json_encode(['success' => false, 'error' => 'An account with this email already exists. Try signing in instead.']);
    exit;
}

$password_hash = password_hash($password, PASSWORD_BCRYPT);
$free_credits = $config['app']['free_credits'];

$stmt = $conn->prepare(
    "INSERT INTO aura_users (name, email, password_hash, plan, credits) VALUES (?, ?, ?, 'free', ?)"
);
$stmt->bind_param('sssi', $name, $email, $password_hash, $free_credits);

if (!$stmt->execute()) {
    error_log('Aura Creator signup failed: ' . $stmt->error);
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Could not create your account. Please try again.']);
    exit;
}

$user_id = $stmt->insert_id;

// Real server-side session — this is what makes the login genuine, not a localStorage flag
$_SESSION['user_id'] = $user_id;
$_SESSION['user_name'] = $name;
$_SESSION['user_email'] = $email;

echo json_encode([
    'success' => true,
    'user' => ['id' => $user_id, 'name' => $name, 'email' => $email, 'plan' => 'free', 'credits' => $free_credits],
]);

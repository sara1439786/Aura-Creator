<?php
// session_check.php — Called on page load to check REAL logged-in state from the server session,
// not from localStorage. Also returns the user's real project list from the DB.

session_start();
header('Content-Type: application/json');
require __DIR__ . '/db.php';
$conn = aura_db();

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => true, 'logged_in' => false]);
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, name, email, plan, credits FROM aura_users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    session_destroy();
    echo json_encode(['success' => true, 'logged_in' => false]);
    exit;
}

// Real projects, most recent first — replaces the old localStorage project list
$stmt = $conn->prepare("SELECT id, name, mode, html_content, created_at FROM aura_projects WHERE user_id = ? ORDER BY created_at DESC LIMIT 20");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$projects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    'success' => true,
    'logged_in' => true,
    'user' => [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'plan' => $user['plan'],
        'credits' => (int)$user['credits'],
    ],
    'projects' => $projects,
]);

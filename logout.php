<?php
// logout.php — Real session destruction.
session_start();
header('Content-Type: application/json');
$_SESSION = [];
session_destroy();
echo json_encode(['success' => true]);

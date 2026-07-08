<?php
// db.php — single shared MySQLi connection for Aura Creator

function aura_db(): mysqli
{
    static $conn = null;

    if ($conn instanceof mysqli) {
        return $conn;
    }

    $config = require __DIR__ . '/config.php';
    $db = $config['db'];

    $conn = new mysqli($db['host'], $db['user'], $db['pass'], $db['name']);

    if ($conn->connect_error) {
        error_log('Aura Creator DB connection failed: ' . $conn->connect_error);
        http_response_code(500);
        die(json_encode(['success' => false, 'error' => 'Server error. Please try again shortly.']));
    }

    $conn->set_charset($db['charset']);
    date_default_timezone_set($config['app']['timezone']);

    return $conn;
}

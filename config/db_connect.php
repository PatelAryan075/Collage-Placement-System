<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

require_once __DIR__ . '/db.php';

$GLOBALS['_db_initialized'] = false;

function initialize_db() {
    if ($GLOBALS['_db_initialized']) return;
    require __DIR__ . '/../database/init_db.php';
    $GLOBALS['_db_initialized'] = true;
}

initialize_db();

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}
?>

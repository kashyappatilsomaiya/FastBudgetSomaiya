<?php
require_once __DIR__ . '/config.php';

/**
 * Require login
 */
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /fastbudget/index.html");
        exit();
    }
}

/**
 * Login user
 */
function login_user($user_id, $user_name, $role) {
    session_regenerate_id(true);
    $_SESSION['user_id']  = (int)$user_id;
    $_SESSION['username'] = $user_name;
    $_SESSION['role']     = $role;
}


/**
 * Require admin (OPTIONAL)
 */
function require_admin() {
    require_login();
    if ($_SESSION['role'] !== 'admin') {
        exit("Access denied");
    }
}

/**
 * Logout
 */
function logout_user() {
    $_SESSION = [];
    session_destroy();
}

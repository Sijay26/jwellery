<?php
/**
 * Authentication and Session Management
 * Royal Aura Luxury Jewellery
 */

require_once __DIR__ . '/functions.php';

/**
 * Check if a customer is currently authenticated
 * @return bool
 */
function is_customer_logged_in() {
    return !empty($_SESSION['user_id']) && !empty($_SESSION['role']) && $_SESSION['role'] === 'customer';
}

/**
 * Check if an admin is currently authenticated
 * @return bool
 */
function is_admin_logged_in() {
    return !empty($_SESSION['admin_id']) && !empty($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Check if any authenticated session exists
 * @return bool
 */
function is_logged_in() {
    return is_customer_logged_in() || is_admin_logged_in();
}

/**
 * Get current logged in customer data
 * @return array|null
 */
function get_current_user_data() {
    if (!is_customer_logged_in()) {
        return null;
    }
    return [
        'id'    => $_SESSION['user_id'],
        'phone' => $_SESSION['phone'] ?? '',
        'role'  => 'customer'
    ];
}

/**
 * Get current logged in admin data
 * @return array|null
 */
function get_current_admin_data() {
    if (!is_admin_logged_in()) {
        return null;
    }
    return [
        'id'    => $_SESSION['admin_id'],
        'phone' => $_SESSION['phone'] ?? '',
        'role'  => 'admin'
    ];
}

/**
 * Require customer login, redirect if not authenticated
 */
function require_customer_login() {
    if (!is_customer_logged_in()) {
        $currentUri = $_SERVER['REQUEST_URI'] ?? 'index.php';
        header("Location: login.php?redirect=" . urlencode($currentUri));
        exit;
    }
}

/**
 * Require admin login, redirect if not authenticated
 */
function require_admin_login() {
    if (!is_admin_logged_in()) {
        header("Location: ../login.php?role=admin");
        exit;
    }
}

/**
 * Authenticate customer session
 * @param array $user
 */
function login_customer_session($user) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['phone'] = $user['phone'];
    $_SESSION['role'] = 'customer';
}

/**
 * Authenticate admin session
 * @param array $admin
 */
function login_admin_session($admin) {
    session_regenerate_id(true);
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['phone'] = $admin['phone'];
    $_SESSION['role'] = 'admin';
}

/**
 * Terminate session completely
 */
function perform_logout() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

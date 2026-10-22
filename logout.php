<?php
/**
 * Logout Handler
 * Clears sessions and cookies, sends no-cache headers, and redirects with empty fields
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

perform_logout();

// Clear browser cache headers to prevent back-button showing cached logged-in screens
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to Login page with empty fields flag
header("Location: login.php?logged_out=1");
exit;

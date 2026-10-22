<?php
/**
 * Login Page with Customer | Admin Toggle
 * Royal Aura Luxury Jewellery
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

// Check if already authenticated
if (empty($_GET['logged_out'])) {
    if (is_admin_logged_in()) {
        header('Location: admin/index.php');
        exit;
    }
    if (is_customer_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

$error = '';
$siteName = get_setting('site_name', 'Royal Aura Luxury Jewellery');
$logoUrl = get_setting('logo', 'assets/images/logo.svg');
$defaultRole = ($_GET['role'] ?? '') === 'admin' ? 'admin' : 'customer';

// Handle POST Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = trim($_POST['role'] ?? 'customer');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($phone) || empty($password)) {
        $error = 'Please enter both phone number and password.';
    } else {
        $pdo = get_db_connection();

        if ($role === 'admin') {
            // Admin authentication
            $stmt = $pdo->prepare("SELECT id, phone, password_hash FROM admins WHERE phone = ? LIMIT 1");
            $stmt->execute([$phone]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                login_admin_session($admin);
                header('Location: admin/index.php');
                exit;
            } else {
                $error = 'Invalid admin phone or password.';
                $defaultRole = 'admin';
            }
        } else {
            // Customer authentication
            $stmt = $pdo->prepare("SELECT id, phone, password_hash FROM users WHERE phone = ? LIMIT 1");
            $stmt->execute([$phone]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                login_customer_session($user);
                $redirectUrl = !empty($_POST['redirect']) ? $_POST['redirect'] : 'index.php';
                header('Location: ' . $redirectUrl);
                exit;
            } else {
                $error = 'Invalid customer credentials. Please check or register a new account.';
                $defaultRole = 'customer';
            }
        }
    }
}

$pageTitle = 'Login • ' . $siteName;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?></title>
  <link rel="icon" type="image/svg+xml" href="<?= e($logoUrl) ?>">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <?php render_theme_styles(); ?>
</head>
<body class="auth-page-wrap">

  <div class="auth-card">
    <!-- Top-Right Customer | Admin Toggle Switch -->
    <div class="auth-role-switch">
      <button 
        type="button" 
        id="role-btn-customer" 
        class="auth-role-btn <?= $defaultRole === 'customer' ? 'active' : '' ?>"
      >
        Customer
      </button>
      <button 
        type="button" 
        id="role-btn-admin" 
        class="auth-role-btn <?= $defaultRole === 'admin' ? 'active' : '' ?>"
      >
        Admin
      </button>
    </div>

    <!-- Brand Emblem -->
    <a href="index.php" style="display: flex; flex-direction: column; align-items: center; text-decoration: none;">
      <img src="<?= e($logoUrl) ?>" alt="<?= e($siteName) ?>" class="auth-brand-logo" />
      <?php if (!str_ends_with(strtolower($logoUrl), '.svg')): ?>
        <div style="text-align: center; margin-top: -0.75rem; margin-bottom: 1.25rem;">
          <div style="font-family: var(--font-serif); font-size: 1.45rem; font-weight: 700; background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; letter-spacing: 2px;"><?= e($siteName) ?></div>
          <div style="font-size: 0.62rem; letter-spacing: 4px; color: var(--accent-light); opacity: 0.85;">HAUTE JOAILLERIE</div>
        </div>
      <?php endif; ?>
    </a>

    <h2 class="auth-title luxury-heading" id="auth-title">
      <?= $defaultRole === 'admin' ? 'Admin Portal Login' : 'Customer Login' ?>
    </h2>
    <p class="auth-subtitle">Welcome to Royal Aura Haute Joaillerie</p>

    <!-- Error Alert -->
    <?php if (!empty($error)): ?>
      <div style="background: rgba(255, 71, 87, 0.15); border: 1px solid #ff4757; color: #ff6b81; padding: 0.8rem 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.88rem; text-align: center;">
        <?= e($error) ?>
      </div>
    <?php endif; ?>

    <?php $flash = get_flash(); if ($flash): ?>
      <div style="background: rgba(34, 197, 94, 0.15); border: 1px solid #22c55e; color: #4ade80; padding: 0.8rem 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.88rem; text-align: center;">
        <?= e($flash['message']) ?>
      </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="login.php" method="POST" autocomplete="off" id="login-form">
      <?php csrf_input(); ?>
      <input type="hidden" name="role" id="auth-role-input" value="<?= e($defaultRole) ?>" />
      <input type="hidden" name="redirect" value="<?= e($_GET['redirect'] ?? 'index.php') ?>" />

      <div class="form-group">
        <label class="form-label" for="login-phone">Mobile Phone Number</label>
        <input 
          type="tel" 
          id="login-phone" 
          name="phone" 
          class="form-control" 
          placeholder="Enter 10-digit number" 
          required 
          autocomplete="off"
        />
      </div>

      <div class="form-group">
        <label class="form-label" for="login-password">Password</label>
        <input 
          type="password" 
          id="login-password" 
          name="password" 
          class="form-control" 
          placeholder="Enter your password" 
          required 
          autocomplete="new-password"
        />
      </div>

      <button type="submit" class="btn btn-gold" style="width: 100%; padding: 0.95rem; margin-top: 0.5rem;">
        <span>✦ Enter Royal Portal</span>
      </button>

      <div id="register-link-wrap" style="text-align: center; margin-top: 1.5rem; font-size: 0.88rem; color: #cbd5e1; <?= $defaultRole === 'admin' ? 'display: none;' : '' ?>">
        Don't have an account? 
        <a href="register.php" style="color: var(--accent); font-weight: 600; text-decoration: underline;">
          Register Now
        </a>
      </div>
    </form>
  </div>

  <script src="assets/js/main.js"></script>
  <script>
    // Strict clear on pageshow to prevent browser history autofill retention
    window.addEventListener('pageshow', function() {
      const f = document.getElementById('login-form');
      if (f) f.reset();
    });
  </script>
</body>
</html>

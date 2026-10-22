<?php
/**
 * Customer Registration Page
 * Royal Aura Luxury Jewellery
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$error = '';
$registrationSuccess = false;
$registeredPhone = '';
$siteName = get_setting('site_name', 'Royal Aura Luxury Jewellery');
$logoUrl = get_setting('logo', 'assets/images/logo.svg');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    // Server-side validation
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = 'Please enter a valid 10-digit mobile phone number.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match. Please re-enter carefully.';
    } else {
        $pdo = get_db_connection();
        // Check if phone already registered
        $stmtCheck = $pdo->prepare("SELECT id FROM users WHERE phone = ? LIMIT 1");
        $stmtCheck->execute([$phone]);
        if ($stmtCheck->fetch()) {
            $error = 'This phone number is already registered. Please log in.';
        } else {
            // Hash password and insert
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (phone, password_hash) VALUES (?, ?)");
            $stmt->execute([$phone, $hash]);
            $newUserId = $pdo->lastInsertId();

            // Auto-login new user session
            login_customer_session([
                'id' => $newUserId,
                'phone' => $phone
            ]);

            $registrationSuccess = true;
            $registeredPhone = $phone;
        }
    }
}

$pageTitle = 'Register Account • ' . $siteName;
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

    <h2 class="auth-title luxury-heading">Create Account</h2>
    <p class="auth-subtitle">Join the Royal Aura Privileged Circle</p>

    <?php if (!empty($error)): ?>
      <div style="background: rgba(255, 71, 87, 0.15); border: 1px solid #ff4757; color: #ff6b81; padding: 0.8rem 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.88rem; text-align: center;">
        <?= e($error) ?>
      </div>
    <?php endif; ?>

    <form action="register.php" method="POST" autocomplete="off" id="register-form" novalidate>
      <?php csrf_input(); ?>

      <div class="form-group">
        <label class="form-label" for="reg-phone">10-Digit Mobile Number *</label>
        <input 
          type="tel" 
          id="reg-phone" 
          name="phone" 
          class="form-control" 
          placeholder="e.g. 9876543210" 
          maxlength="10" 
          value="<?= e($_POST['phone'] ?? '') ?>"
          required 
          autocomplete="off"
        />
      </div>

      <div class="form-group">
        <label class="form-label" for="reg-password">Create Password (min 6 characters) *</label>
        <input 
          type="password" 
          id="reg-password" 
          name="password" 
          class="form-control" 
          placeholder="Choose a strong password" 
          required 
          autocomplete="new-password"
        />
      </div>

      <div class="form-group">
        <label class="form-label" for="reg-confirm-password">Confirm Password *</label>
        <input 
          type="password" 
          id="reg-confirm-password" 
          name="confirm_password" 
          class="form-control" 
          placeholder="Re-enter password" 
          required 
          autocomplete="new-password"
        />
      </div>

      <button type="submit" class="btn btn-gold" style="width: 100%; padding: 0.95rem; margin-top: 0.5rem;">
        <span>✦ Register & Discover Jewellery</span>
      </button>

      <div style="text-align: center; margin-top: 1.5rem; font-size: 0.88rem; color: #cbd5e1;">
        Already have an account? 
        <a href="login.php" style="color: var(--accent); font-weight: 600; text-decoration: underline;">
          Log In Here
        </a>
      </div>
    </form>
  </div>

  <!-- Registration Success "OK" Luxury Popup Modal -->
  <?php if ($registrationSuccess): ?>
    <div id="register-success-modal" class="modal-overlay active">
      <div class="modal-content" style="text-align: center; max-width: 440px;">
        <div style="font-size: 3.5rem; color: var(--accent); margin-bottom: 0.5rem; filter: drop-shadow(0 0 15px rgba(212,175,55,0.6));">
          👑
        </div>
        <h3 class="luxury-heading" style="font-size: 1.8rem; margin-bottom: 0.6rem;">Welcome to Royalty!</h3>
        <p style="color: #cbd5e1; font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.6;">
          Your privileged account for <strong>+91 <?= e($registeredPhone) ?></strong> has been created successfully.
        </p>
        <a href="index.php" class="btn btn-gold" style="width: 100%; padding: 0.85rem; font-size: 1rem;">
          <span>OK • Enter Royal Aura</span>
        </a>
      </div>
    </div>
  <?php endif; ?>

  <script src="assets/js/main.js"></script>
</body>
</html>

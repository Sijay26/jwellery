<?php
/**
 * Order Success Page with 3-Second Automatic Redirect to Home
 * Royal Aura Luxury Jewellery
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

require_customer_login();

$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$pdo = get_db_connection();

$order = null;
if ($orderId > 0) {
    $stmt = $pdo->prepare("
        SELECT o.*, p.name AS product_name, p.image, p.price_inr, p.model, p.weight_grams
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.id = ?
    ");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
}

$siteName = get_setting('site_name', 'Royal Aura Luxury Jewellery');
$pageTitle = 'Order Confirmed • ' . $siteName;

require_once __DIR__ . '/includes/header.php';
?>

  <div class="container" style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 4rem 1.5rem;">
    <div style="background: var(--gradient-surface); border: 1px solid var(--glass-border); border-radius: 24px; padding: 3rem 2.5rem; max-width: 560px; width: 100%; text-align: center; box-shadow: var(--shadow-lg), var(--shadow-purple); backdrop-filter: blur(25px);">
      <div style="font-size: 4rem; color: var(--accent); margin-bottom: 1rem; filter: drop-shadow(0 0 20px rgba(212,175,55,0.7)); animation: pulseGlow 2s infinite;">
        ✦
      </div>

      <h1 class="luxury-heading" style="font-size: 2.2rem; margin-bottom: 0.5rem;">
        Thank You For Your Order!
      </h1>
      <p style="color: #cbd5e1; font-size: 1.05rem; margin-bottom: 1.5rem;">
        Your royal booking #<strong><?= (int)$orderId ?></strong> has been received by our master concierge.
      </p>

      <?php if ($order): ?>
        <div style="display: flex; gap: 1rem; align-items: center; background: rgba(255,255,255,0.04); border: 1px solid var(--glass-border); border-radius: 14px; padding: 1rem; margin-bottom: 1.5rem; text-align: left;">
          <img src="<?= e($order['image']) ?>" alt="<?= e($order['product_name']) ?>" style="width: 65px; height: 65px; border-radius: 10px; object-fit: cover; border: 1px solid var(--glass-border);" />
          <div>
            <h4 style="font-family: var(--font-serif); font-size: 1.05rem; color: #fff; margin-bottom: 0.2rem;"><?= e($order['product_name']) ?></h4>
            <div style="font-size: 0.8rem; color: var(--text-dim);">Model: <?= e($order['model']) ?> • ⚖ <?= e($order['weight_grams']) ?>g</div>
            <div style="color: var(--accent-light); font-weight: 700; font-size: 1rem;"><?= format_inr($order['price_inr']) ?></div>
          </div>
        </div>
      <?php endif; ?>

      <p style="color: #94a3b8; font-size: 0.88rem; line-height: 1.6;">
        We will contact your registered phone number <strong>+91 <?= e($order['phone'] ?? '') ?></strong> to verify delivery coordinates and insured transit.
      </p>

      <!-- 3-Second Automatic Redirect Progress Bar -->
      <div class="redirect-progress-bar">
        <div class="redirect-progress-fill" id="redirect-fill"></div>
      </div>
      <p style="font-size: 0.8rem; color: var(--accent); margin-top: 0.8rem;">
        Automatically returning to home in 3 seconds...
      </p>

      <div style="margin-top: 1.5rem;">
        <a href="index.php" class="btn btn-outline-gold btn-sm">
          Return to Catalogue Now
        </a>
      </div>
    </div>
  </div>

  <script>
    // Trigger animated progress fill and 3-second auto-redirect
    setTimeout(function() {
      var fill = document.getElementById('redirect-fill');
      if (fill) fill.style.width = '100%';
    }, 50);

    setTimeout(function() {
      window.location.href = 'index.php';
    }, 3000);
  </script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

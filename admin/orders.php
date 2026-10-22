<?php
/**
 * Order Management - Royal Aura Luxury Jewellery
 * View all customer orders and update status (Pending, Confirmed, Delivered)
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$adminTitle = 'Customer Orders';
$pdo = get_db_connection();

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = (int)$_POST['order_id'];
    $newStatus = trim($_POST['status']);

    $allowedStatuses = ['Pending', 'Confirmed', 'Delivered'];
    if (in_array($newStatus, $allowedStatuses)) {
        $stmtUp = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmtUp->execute([$newStatus, $orderId]);
        set_flash('success', "Order #{$orderId} status successfully updated to '{$newStatus}'.");
    } else {
        set_flash('error', 'Invalid order status selected.');
    }
    header('Location: orders.php');
    exit;
}

// Filter by status if requested
$filter = $_GET['filter'] ?? 'all';
$query = "
    SELECT o.*, p.name AS product_name, p.image, p.price_inr, p.model, p.weight_grams
    FROM orders o
    JOIN products p ON o.product_id = p.id
";

if (in_array($filter, ['Pending', 'Confirmed', 'Delivered'])) {
    $query .= " WHERE o.status = " . $pdo->quote($filter);
}
$query .= " ORDER BY o.id DESC";

$orders = $pdo->query($query)->fetchAll();

require_once __DIR__ . '/header.php';
?>

  <!-- Filter Buttons Row -->
  <div style="display: flex; gap: 0.75rem; margin-bottom: 2rem; flex-wrap: wrap;">
    <a href="orders.php?filter=all" class="btn btn-sm <?= $filter === 'all' ? 'btn-gold' : 'btn-outline-gold' ?>">
      All Orders (<?= count($orders) ?>)
    </a>
    <a href="orders.php?filter=Pending" class="btn btn-sm <?= $filter === 'Pending' ? 'btn-gold' : 'btn-outline-gold' ?>">
      ⏳ Pending
    </a>
    <a href="orders.php?filter=Confirmed" class="btn btn-sm <?= $filter === 'Confirmed' ? 'btn-gold' : 'btn-outline-gold' ?>">
      ✓ Confirmed
    </a>
    <a href="orders.php?filter=Delivered" class="btn btn-sm <?= $filter === 'Delivered' ? 'btn-gold' : 'btn-outline-gold' ?>">
      🚚 Delivered
    </a>
  </div>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">All Customer Bookings & Orders</h3>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Client Name</th>
            <th>Phone</th>
            <th>Delivery Address</th>
            <th>Jewellery Reserved</th>
            <th>Total INR</th>
            <th>Date</th>
            <th>Status Update</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($orders)): ?>
            <tr>
              <td colspan="8" style="text-align: center; padding: 2.5rem;">No customer orders found in this view.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($orders as $ord): 
              $imgSrc = str_starts_with($ord['image'], 'http') ? $ord['image'] : '../' . $ord['image'];
              $badgeClass = 'badge-pending';
              if ($ord['status'] === 'Confirmed') $badgeClass = 'badge-confirmed';
              if ($ord['status'] === 'Delivered') $badgeClass = 'badge-delivered';
            ?>
              <tr>
                <td><strong>#<?= (int)$ord['id'] ?></strong></td>
                <td>
                  <strong style="color: #fff; font-size: 0.95rem;"><?= e($ord['customer_name']) ?></strong>
                </td>
                <td>
                  <a href="tel:<?= e($ord['phone']) ?>" style="color: var(--accent-light); font-weight: 600;">
                    +91 <?= e($ord['phone']) ?>
                  </a>
                  <br />
                  <a 
                    href="https://wa.me/91<?= e($ord['phone']) ?>?text=Hello%20<?= urlencode($ord['customer_name']) ?>%2C%20regarding%20your%20Royal%20Aura%20Order%20%23<?= (int)$ord['id'] ?>%20for%20<?= urlencode($ord['product_name']) ?>..." 
                    target="_blank" 
                    style="font-size: 0.75rem; color: #4ade80;"
                  >
                    💬 Message on WhatsApp
                  </a>
                </td>
                <td style="max-width: 220px; font-size: 0.85rem; line-height: 1.4;">
                  <?= nl2br(e($ord['address'])) ?>
                </td>
                <td>
                  <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <img src="<?= e($imgSrc) ?>" alt="" class="table-thumb" />
                    <div>
                      <strong style="color: #fff; font-size: 0.9rem;"><?= e($ord['product_name']) ?></strong>
                      <div style="font-size: 0.78rem; color: var(--text-dim);">Model: <?= e($ord['model']) ?> • ⚖ <?= e($ord['weight_grams']) ?>g</div>
                    </div>
                  </div>
                </td>
                <td style="color: var(--accent-light); font-weight: 700; font-size: 1.05rem;">
                  <?= format_inr($ord['price_inr']) ?>
                </td>
                <td style="font-size: 0.82rem;">
                  <?= date('M d, Y', strtotime($ord['created_at'])) ?><br />
                  <span style="color: var(--text-dim);"><?= date('h:i A', strtotime($ord['created_at'])) ?></span>
                </td>
                <td>
                  <!-- Instant Status Change Form -->
                  <form action="orders.php" method="POST" style="display: flex; align-items: center; gap: 0.5rem;">
                    <?php csrf_input(); ?>
                    <input type="hidden" name="order_id" value="<?= (int)$ord['id'] ?>" />
                    <select 
                      name="status" 
                      onchange="this.form.submit()" 
                      class="form-control" 
                      style="padding: 0.4rem 0.6rem; font-size: 0.82rem; border-radius: 8px; width: auto; background: rgba(20,2,36,0.9); border-color: var(--glass-border);"
                    >
                      <option value="Pending" <?= $ord['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                      <option value="Confirmed" <?= $ord['status'] === 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                      <option value="Delivered" <?= $ord['status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                    </select>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

<?php require_once __DIR__ . '/footer.php'; ?>

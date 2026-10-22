<?php
/**
 * Admin Dashboard - Royal Aura Luxury Jewellery
 * Metrics, recent orders, and recent enquiries
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$adminTitle = 'Dashboard Overview';
$pdo = get_db_connection();

// 1. Fetch Summary Counts
$countProducts = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$countOrders = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$countEnquiries = (int)$pdo->query("SELECT COUNT(*) FROM enquiries")->fetchColumn();
$countUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// 2. Fetch Recent Orders (last 5)
$stmtRecentOrders = $pdo->query("
    SELECT o.*, p.name AS product_name, p.image, p.price_inr 
    FROM orders o
    JOIN products p ON o.product_id = p.id
    ORDER BY o.id DESC
    LIMIT 5
");
$recentOrders = $stmtRecentOrders->fetchAll();

// 3. Fetch Recent Enquiries (last 5)
$stmtRecentEnq = $pdo->query("
    SELECT e.*, p.name AS product_name, p.model, p.price_inr, p.image, u.phone AS user_phone
    FROM enquiries e
    JOIN products p ON e.product_id = p.id
    LEFT JOIN users u ON e.user_id = u.id
    ORDER BY e.id DESC
    LIMIT 5
");
$recentEnquiries = $stmtRecentEnq->fetchAll();

require_once __DIR__ . '/header.php';
?>

  <!-- Statistics Overview Grid -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon-wrap">💎</div>
      <div class="stat-info">
        <div class="stat-number"><?= $countProducts ?></div>
        <div class="stat-label">Total Jewellery Items</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon-wrap">🛍</div>
      <div class="stat-info">
        <div class="stat-number"><?= $countOrders ?></div>
        <div class="stat-label">Total Orders Placed</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon-wrap">💬</div>
      <div class="stat-info">
        <div class="stat-number"><?= $countEnquiries ?></div>
        <div class="stat-label">Customer Enquiries</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon-wrap">👑</div>
      <div class="stat-info">
        <div class="stat-number"><?= $countUsers ?></div>
        <div class="stat-label">Registered Clients</div>
      </div>
    </div>
  </div>

  <!-- Quick Administrative Action Shortcuts -->
  <div style="display: flex; gap: 1rem; margin-bottom: 2.5rem; flex-wrap: wrap;">
    <a href="products.php#add-product" class="btn btn-gold btn-sm">
      <span>+ Add New Jewellery Piece</span>
    </a>
    <a href="categories.php" class="btn btn-outline-gold btn-sm">
      <span>+ Manage Categories</span>
    </a>
    <a href="settings.php" class="btn btn-outline-gold btn-sm">
      <span>🎨 Customize Theme Color</span>
    </a>
  </div>

  <!-- Recent Orders Table -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Recent Customer Orders</h3>
      <a href="orders.php" class="btn btn-outline-gold btn-sm">View All Orders (<?= $countOrders ?>) ➔</a>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Client</th>
            <th>Phone</th>
            <th>Jewellery</th>
            <th>Price</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentOrders)): ?>
            <tr>
              <td colspan="7" style="text-align: center; padding: 2rem;">No orders placed yet. Orders will appear here.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($recentOrders as $ord): 
              $badgeClass = 'badge-pending';
              if ($ord['status'] === 'Confirmed') $badgeClass = 'badge-confirmed';
              if ($ord['status'] === 'Delivered') $badgeClass = 'badge-delivered';
              $imgSrc = str_starts_with($ord['image'], 'http') ? $ord['image'] : '../' . $ord['image'];
            ?>
              <tr>
                <td><strong>#<?= (int)$ord['id'] ?></strong></td>
                <td><strong style="color: #fff;"><?= e($ord['customer_name']) ?></strong></td>
                <td><?= e($ord['phone']) ?></td>
                <td>
                  <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <img src="<?= e($imgSrc) ?>" alt="" class="table-thumb" />
                    <span><?= e($ord['product_name']) ?></span>
                  </div>
                </td>
                <td style="color: var(--accent-light); font-weight: 600;"><?= format_inr($ord['price_inr']) ?></td>
                <td><?= date('M d, Y h:i A', strtotime($ord['created_at'])) ?></td>
                <td>
                  <span class="badge-status <?= $badgeClass ?>"><?= e($ord['status']) ?></span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Recent Customer Enquiries -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Recent Product Enquiries</h3>
      <a href="enquiries.php" class="btn btn-outline-gold btn-sm">View All Enquiries (<?= $countEnquiries ?>) ➔</a>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Enquiry #</th>
            <th>Customer Phone</th>
            <th>Jewellery Inquired</th>
            <th>Model Code</th>
            <th>Date & Time</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentEnquiries)): ?>
            <tr>
              <td colspan="6" style="text-align: center; padding: 2rem;">No enquiries recorded yet.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($recentEnquiries as $enq): 
              $imgSrc = str_starts_with($enq['image'], 'http') ? $enq['image'] : '../' . $enq['image'];
              $clientPhone = $enq['user_phone'] ?? 'Guest Customer';
            ?>
              <tr>
                <td><strong>#<?= (int)$enq['id'] ?></strong></td>
                <td><strong style="color: #fff;"><?= e($clientPhone) ?></strong></td>
                <td>
                  <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <img src="<?= e($imgSrc) ?>" alt="" class="table-thumb" />
                    <span><?= e($enq['product_name']) ?></span>
                  </div>
                </td>
                <td><span class="user-badge" style="font-size: 0.75rem;"><?= e($enq['model']) ?></span></td>
                <td><?= date('M d, Y h:i A', strtotime($enq['created_at'])) ?></td>
                <td>
                  <?php if (!empty($enq['user_phone'])): ?>
                    <a 
                      href="https://wa.me/91<?= e($enq['user_phone']) ?>?text=Hello%2C%20thank%20you%20for%20inquiring%20about%20<?= urlencode($enq['product_name']) ?>" 
                      target="_blank" 
                      class="btn btn-sm btn-outline-gold"
                    >
                      💬 Reply WhatsApp
                    </a>
                  <?php else: ?>
                    <span style="color: var(--text-dim); font-size: 0.8rem;">Logged from Home</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

<?php require_once __DIR__ . '/footer.php'; ?>

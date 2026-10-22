<?php
/**
 * Customer Enquiries Management - Royal Aura Luxury Jewellery
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$adminTitle = 'Customer Enquiries';
$pdo = get_db_connection();

// Delete enquiry if requested
if (isset($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $stmtDel = $pdo->prepare("DELETE FROM enquiries WHERE id = ?");
    $stmtDel->execute([$delId]);
    set_flash('success', 'Enquiry #' . $delId . ' deleted.');
    header('Location: enquiries.php');
    exit;
}

$stmt = $pdo->query("
    SELECT e.*, p.name AS product_name, p.model, p.price_inr, p.image, p.weight_grams, u.phone AS user_phone
    FROM enquiries e
    JOIN products p ON e.product_id = p.id
    LEFT JOIN users u ON e.user_id = u.id
    ORDER BY e.id DESC
");
$enquiries = $stmt->fetchAll();

require_once __DIR__ . '/header.php';
?>

  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">All Customer Product Inquiries (<?= count($enquiries) ?>)</h3>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Enquiry #</th>
            <th>Customer Phone</th>
            <th>Jewellery Inquired</th>
            <th>Model Code</th>
            <th>Weight</th>
            <th>Price (INR)</th>
            <th>Date & Time</th>
            <th>WhatsApp Follow-Up</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($enquiries)): ?>
            <tr>
              <td colspan="9" style="text-align: center; padding: 2.5rem;">No enquiries recorded yet.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($enquiries as $enq): 
              $imgSrc = str_starts_with($enq['image'], 'http') ? $enq['image'] : '../' . $enq['image'];
              $clientPhone = $enq['user_phone'] ?? null;
            ?>
              <tr>
                <td><strong>#<?= (int)$enq['id'] ?></strong></td>
                <td>
                  <?php if ($clientPhone): ?>
                    <strong style="color: #fff; font-size: 0.95rem;">+91 <?= e($clientPhone) ?></strong>
                  <?php else: ?>
                    <span style="color: var(--text-dim); font-size: 0.85rem;">Website Visitor</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <img src="<?= e($imgSrc) ?>" alt="" class="table-thumb" />
                    <div>
                      <strong style="color: #fff; font-size: 0.9rem;"><?= e($enq['product_name']) ?></strong>
                    </div>
                  </div>
                </td>
                <td><code><?= e($enq['model']) ?></code></td>
                <td>⚖ <?= number_format($enq['weight_grams'], 2) ?> g</td>
                <td style="color: var(--accent-light); font-weight: 700;"><?= format_inr($enq['price_inr']) ?></td>
                <td style="font-size: 0.82rem;">
                  <?= date('M d, Y', strtotime($enq['created_at'])) ?><br />
                  <span style="color: var(--text-dim);"><?= date('h:i A', strtotime($enq['created_at'])) ?></span>
                </td>
                <td>
                  <?php if ($clientPhone): ?>
                    <a 
                      href="https://wa.me/91<?= e($clientPhone) ?>?text=Hello%2C%20thank%20you%20for%20your%20interest%20in%20Royal%20Aura%20<?= urlencode($enq['product_name']) ?>%20(Model%3A%20<?= urlencode($enq['model']) ?>%2C%20Price%3A%20<?= urlencode(format_inr($enq['price_inr'])) ?>).%20How%20may%20our%20master%20jeweller%20assist%20you%20today%3F" 
                      target="_blank" 
                      class="btn btn-sm btn-gold"
                    >
                      💬 Chat on WhatsApp
                    </a>
                  <?php else: ?>
                    <a 
                      href="https://wa.me/916369058169?text=Customer%20inquiry%20logged%20for%20<?= urlencode($enq['product_name']) ?>" 
                      target="_blank" 
                      class="btn btn-sm btn-outline-gold"
                    >
                      💬 Open Concierge Desk
                    </a>
                  <?php endif; ?>
                </td>
                <td>
                  <a 
                    href="enquiries.php?delete=<?= (int)$enq['id'] ?>" 
                    class="btn btn-outline-gold btn-sm btn-confirm-delete"
                    data-title="Enquiry #<?= (int)$enq['id'] ?>"
                    style="border-color: #ef4444; color: #f87171;"
                  >
                    🗑
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

<?php require_once __DIR__ . '/footer.php'; ?>

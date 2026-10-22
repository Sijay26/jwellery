<?php
/**
 * Category Management - Royal Aura Luxury Jewellery
 * Add, Rename, Change Image, and Delete Categories
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$adminTitle = 'Manage Jewellery Categories';
$pdo = get_db_connection();

$action = $_GET['action'] ?? 'list';
$catId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$categoryToEdit = null;

if ($action === 'edit' && $catId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$catId]);
    $categoryToEdit = $stmt->fetch();
}

// 1. Handle Delete Action
if ($action === 'delete' && $catId > 0) {
    $stmtDel = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmtDel->execute([$catId]);
    set_flash('success', 'Category and its products were successfully deleted.');
    header('Location: categories.php');
    exit;
}

// 2. Handle Add / Edit Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $isEdit = !empty($_POST['edit_id']);
    $targetEditId = (int)($_POST['edit_id'] ?? 0);

    if (empty($name)) {
        set_flash('error', 'Category name cannot be empty.');
    } else {
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploaded = handle_image_upload($_FILES['image'], 'uploads/');
            if ($uploaded) {
                $imagePath = $uploaded;
            } else {
                set_flash('error', 'Invalid category image file.');
            }
        }

        if ($isEdit && $targetEditId > 0) {
            if ($imagePath) {
                $stmtUp = $pdo->prepare("UPDATE categories SET name = ?, image = ? WHERE id = ?");
                $stmtUp->execute([$name, $imagePath, $targetEditId]);
            } else {
                $stmtUp = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
                $stmtUp->execute([$name, $targetEditId]);
            }
            set_flash('success', 'Category "' . $name . '" was updated successfully!');
            header('Location: categories.php');
            exit;
        } else {
            if (!$imagePath) {
                $imagePath = 'assets/images/cat_ring.svg';
            }
            $stmtIns = $pdo->prepare("INSERT INTO categories (name, image) VALUES (?, ?)");
            $stmtIns->execute([$name, $imagePath]);
            set_flash('success', 'New category "' . $name . '" created! It now appears on the customer storefront.');
            header('Location: categories.php');
            exit;
        }
    }
}

// Fetch all categories with product count
$categories = $pdo->query("
    SELECT c.*, COUNT(p.id) AS product_count 
    FROM categories c
    LEFT JOIN products p ON c.id = p.category_id
    GROUP BY c.id
    ORDER BY c.id ASC
")->fetchAll();

require_once __DIR__ . '/header.php';
?>

  <!-- Add or Edit Category Card -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">
        <?= $categoryToEdit ? '✦ Edit Category: ' . e($categoryToEdit['name']) : '✦ Add New Jewellery Category' ?>
      </h3>
      <?php if ($categoryToEdit): ?>
        <a href="categories.php" class="btn btn-outline-gold btn-sm">Cancel Edit</a>
      <?php endif; ?>
    </div>

    <form action="categories.php" method="POST" enctype="multipart/form-data" autocomplete="off">
      <?php csrf_input(); ?>
      <?php if ($categoryToEdit): ?>
        <input type="hidden" name="edit_id" value="<?= (int)$categoryToEdit['id'] ?>" />
      <?php endif; ?>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; align-items: end;">
        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">Category Name *</label>
          <input 
            type="text" 
            name="name" 
            class="form-control" 
            placeholder="e.g. Choker, Anklet, Mangalsutra" 
            value="<?= e($categoryToEdit['name'] ?? '') ?>" 
            required
          />
        </div>

        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label">Category Cover Image</label>
          <input 
            type="file" 
            name="image" 
            class="form-control" 
            accept="image/png, image/jpeg, image/webp, image/svg+xml"
          />
          <?php if ($categoryToEdit && !empty($categoryToEdit['image'])): 
            $catImg = str_starts_with($categoryToEdit['image'], 'http') ? $categoryToEdit['image'] : '../' . $categoryToEdit['image'];
          ?>
            <div style="margin-top: 0.4rem; display: flex; align-items: center; gap: 0.5rem;">
              <span style="font-size: 0.78rem; color: var(--accent);">Current:</span>
              <img src="<?= e($catImg) ?>" alt="" style="width: 40px; height: 32px; border-radius: 6px; object-fit: cover; border: 1px solid var(--glass-border);" />
            </div>
          <?php endif; ?>
        </div>

        <div>
          <button type="submit" class="btn btn-gold" style="width: 100%;">
            <span><?= $categoryToEdit ? '✦ Save Category Changes' : '✦ Add Category' ?></span>
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- Categories Table -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">Live Storefront Categories (<?= count($categories) ?>)</h3>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Visual</th>
            <th>Category Name</th>
            <th>Total Products</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($categories as $cat): 
            $imgSrc = str_starts_with($cat['image'], 'http') ? $cat['image'] : '../' . $cat['image'];
          ?>
            <tr>
              <td><strong>#<?= (int)$cat['id'] ?></strong></td>
              <td>
                <img src="<?= e($imgSrc) ?>" alt="" class="table-thumb" style="aspect-ratio: 4/3; width: 60px; height: 45px;" />
              </td>
              <td>
                <strong style="color: #fff; font-size: 1.05rem;"><?= e($cat['name']) ?></strong>
              </td>
              <td>
                <span class="user-badge" style="font-size: 0.8rem;">💎 <?= (int)$cat['product_count'] ?> Pieces</span>
              </td>
              <td><?= date('M d, Y', strtotime($cat['created_at'])) ?></td>
              <td>
                <div style="display: flex; gap: 0.5rem;">
                  <a href="categories.php?action=edit&id=<?= (int)$cat['id'] ?>" class="btn btn-outline-gold btn-sm">
                    ✏ Rename / Image
                  </a>
                  <a 
                    href="categories.php?action=delete&id=<?= (int)$cat['id'] ?>" 
                    class="btn btn-outline-gold btn-sm btn-confirm-delete" 
                    data-title="<?= e($cat['name']) ?>"
                    style="border-color: #ef4444; color: #f87171;"
                  >
                    🗑
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

<?php require_once __DIR__ . '/footer.php'; ?>

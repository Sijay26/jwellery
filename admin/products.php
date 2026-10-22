<?php
/**
 * Product Management - Royal Aura Luxury Jewellery
 * Add, Edit, Delete Jewellery items with Image Upload
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$adminTitle = 'Manage Jewellery Products';
$pdo = get_db_connection();

$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC")->fetchAll();

$action = $_GET['action'] ?? 'list';
$editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$productToEdit = null;

if ($action === 'edit' && $editId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$editId]);
    $productToEdit = $stmt->fetch();
}

// 1. Handle Delete Action
if ($action === 'delete' && $editId > 0) {
    $stmtDel = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmtDel->execute([$editId]);
    set_flash('success', 'Jewellery item #' . $editId . ' was successfully deleted.');
    header('Location: products.php');
    exit;
}

// 2. Handle Add / Edit Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $priceInr = (float)($_POST['price_inr'] ?? 0);
    $model = trim($_POST['model'] ?? '');
    $weightGrams = (float)($_POST['weight_grams'] ?? 0);
    $details = trim($_POST['details'] ?? '');
    $isEdit = !empty($_POST['edit_id']);
    $targetEditId = (int)($_POST['edit_id'] ?? 0);

    if (empty($name) || $categoryId <= 0 || $priceInr <= 0 || empty($model) || $weightGrams <= 0) {
        set_flash('error', 'Please fill in all required fields with valid values.');
    } else {
        // Handle Image Upload if provided
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploaded = handle_image_upload($_FILES['image'], 'uploads/');
            if ($uploaded) {
                $imagePath = $uploaded;
            } else {
                set_flash('error', 'Failed to upload image. Allowed formats: JPG, PNG, WEBP, SVG under 6MB.');
            }
        }

        if ($isEdit && $targetEditId > 0) {
            if ($imagePath) {
                $stmtUp = $pdo->prepare("
                    UPDATE products 
                    SET category_id = ?, name = ?, image = ?, price_inr = ?, model = ?, weight_grams = ?, details = ?
                    WHERE id = ?
                ");
                $stmtUp->execute([$categoryId, $name, $imagePath, $priceInr, $model, $weightGrams, $details, $targetEditId]);
            } else {
                $stmtUp = $pdo->prepare("
                    UPDATE products 
                    SET category_id = ?, name = ?, price_inr = ?, model = ?, weight_grams = ?, details = ?
                    WHERE id = ?
                ");
                $stmtUp->execute([$categoryId, $name, $priceInr, $model, $weightGrams, $details, $targetEditId]);
            }
            set_flash('success', 'Jewellery piece "' . $name . '" updated successfully!');
            header('Location: products.php');
            exit;
        } else {
            // New Product: default fallback image if not uploaded
            if (!$imagePath) {
                $imagePath = 'assets/images/prod_ring_1.svg';
            }
            $stmtIns = $pdo->prepare("
                INSERT INTO products (category_id, name, image, price_inr, model, weight_grams, details)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmtIns->execute([$categoryId, $name, $imagePath, $priceInr, $model, $weightGrams, $details]);
            set_flash('success', 'New jewellery piece "' . $name . '" added successfully to catalog!');
            header('Location: products.php');
            exit;
        }
    }
}

// Fetch all products with category names
$products = $pdo->query("
    SELECT p.*, c.name AS category_name 
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id DESC
")->fetchAll();

require_once __DIR__ . '/header.php';
?>

  <!-- Add or Edit Form Section -->
  <div class="admin-card" id="add-product">
    <div class="admin-card-header">
      <h3 class="admin-card-title">
        <?= $productToEdit ? '✦ Edit Jewellery Piece #' . (int)$productToEdit['id'] : '✦ Add New Luxury Jewellery Piece' ?>
      </h3>
      <?php if ($productToEdit): ?>
        <a href="products.php" class="btn btn-outline-gold btn-sm">Cancel Edit</a>
      <?php endif; ?>
    </div>

    <form action="products.php" method="POST" enctype="multipart/form-data" autocomplete="off">
      <?php csrf_input(); ?>
      <?php if ($productToEdit): ?>
        <input type="hidden" name="edit_id" value="<?= (int)$productToEdit['id'] ?>" />
      <?php endif; ?>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        <div class="form-group">
          <label class="form-label">Jewellery Name *</label>
          <input 
            type="text" 
            name="name" 
            class="form-control" 
            placeholder="e.g. Royal Nizam Emerald Choker" 
            value="<?= e($productToEdit['name'] ?? '') ?>" 
            required
          />
        </div>

        <div class="form-group">
          <label class="form-label">Category *</label>
          <select name="category_id" class="form-control" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
              <option 
                value="<?= (int)$cat['id'] ?>"
                <?= ($productToEdit && (int)$productToEdit['category_id'] === (int)$cat['id']) ? 'selected' : '' ?>
              >
                <?= e($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Price in INR (₹) *</label>
          <input 
            type="number" 
            name="price_inr" 
            step="0.01" 
            class="form-control" 
            placeholder="e.g. 275000" 
            value="<?= e($productToEdit['price_inr'] ?? '') ?>" 
            required
          />
        </div>

        <div class="form-group">
          <label class="form-label">Model Code *</label>
          <input 
            type="text" 
            name="model" 
            class="form-control" 
            placeholder="e.g. HRM-105" 
            value="<?= e($productToEdit['model'] ?? '') ?>" 
            required
          />
        </div>

        <div class="form-group">
          <label class="form-label">Weight in Grams (22K) *</label>
          <input 
            type="number" 
            name="weight_grams" 
            step="0.01" 
            class="form-control" 
            placeholder="e.g. 45.20" 
            value="<?= e($productToEdit['weight_grams'] ?? '') ?>" 
            required
          />
        </div>

        <div class="form-group">
          <label class="form-label">Jewellery Image (Upload)</label>
          <input 
            type="file" 
            name="image" 
            class="form-control image-upload-input" 
            data-preview="product-img-preview"
            accept="image/png, image/jpeg, image/webp, image/svg+xml"
          />
          <small style="color: var(--text-dim); font-size: 0.78rem;">Upload JPG, PNG, WEBP, or SVG under 6MB.</small>
          <?php if ($productToEdit && !empty($productToEdit['image'])): 
            $currentImg = str_starts_with($productToEdit['image'], 'http') ? $productToEdit['image'] : '../' . $productToEdit['image'];
          ?>
            <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.75rem;">
              <span style="font-size: 0.78rem; color: var(--accent);">Current:</span>
              <img src="<?= e($currentImg) ?>" alt="" style="width: 45px; height: 45px; border-radius: 6px; border: 1px solid var(--glass-border); object-fit: cover;" />
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Artisan Details & Specifications *</label>
        <textarea 
          name="details" 
          rows="3" 
          class="form-control" 
          placeholder="Describe gold karat purity, gemstones, craftsmanship details, certifications..." 
          required
        ><?= e($productToEdit['details'] ?? '') ?></textarea>
      </div>

      <button type="submit" class="btn btn-gold">
        <span><?= $productToEdit ? '✦ Save Changes to Product' : '✦ Add Product to Royal Catalogue' ?></span>
      </button>
    </form>
  </div>

  <!-- Existing Products Table -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">All Catalogue Jewellery Items (<?= count($products) ?>)</h3>
      <a href="#add-product" class="btn btn-gold btn-sm">+ Add New Item</a>
    </div>

    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Jewellery Name</th>
            <th>Category</th>
            <th>Model</th>
            <th>Weight</th>
            <th>Price (INR)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($products)): ?>
            <tr>
              <td colspan="8" style="text-align: center; padding: 2rem;">No products found in catalogue.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($products as $p): 
              $imgSrc = str_starts_with($p['image'], 'http') ? $p['image'] : '../' . $p['image'];
            ?>
              <tr>
                <td><strong>#<?= (int)$p['id'] ?></strong></td>
                <td>
                  <img src="<?= e($imgSrc) ?>" alt="" class="table-thumb" />
                </td>
                <td>
                  <strong style="color: #fff; font-size: 0.95rem;"><?= e($p['name']) ?></strong>
                </td>
                <td><span class="user-badge" style="font-size: 0.75rem;"><?= e($p['category_name']) ?></span></td>
                <td><code><?= e($p['model']) ?></code></td>
                <td>⚖ <?= number_format($p['weight_grams'], 2) ?> g</td>
                <td style="color: var(--accent-light); font-weight: 700; font-size: 1rem;">
                  <?= format_inr($p['price_inr']) ?>
                </td>
                <td>
                  <div style="display: flex; gap: 0.5rem;">
                    <a href="products.php?action=edit&id=<?= (int)$p['id'] ?>" class="btn btn-outline-gold btn-sm" title="Edit">
                      ✏ Edit
                    </a>
                    <a 
                      href="products.php?action=delete&id=<?= (int)$p['id'] ?>" 
                      class="btn btn-outline-gold btn-sm btn-confirm-delete" 
                      data-title="<?= e($p['name']) ?>"
                      style="border-color: #ef4444; color: #f87171;"
                      title="Delete"
                    >
                      🗑
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

<?php require_once __DIR__ . '/footer.php'; ?>

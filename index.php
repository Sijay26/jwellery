<?php
/**
 * Customer Home Page - Royal Aura Luxury Jewellery
 * Royal purple luxury aesthetics, live search, categories, product grid, Buy Now & WhatsApp Enquiry
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

// Protect page: redirect to login if not authenticated
require_customer_login();

$pdo = get_db_connection();
$selectedCatId = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Fetch all categories dynamically
$stmtCat = $pdo->query("SELECT id, name, image FROM categories ORDER BY id ASC");
$categories = $stmtCat->fetchAll();

// Fetch products (filtered by category if selected)
if ($selectedCatId > 0) {
    $stmtProd = $pdo->prepare("
        SELECT p.*, c.name AS category_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.category_id = ?
        ORDER BY p.id DESC
    ");
    $stmtProd->execute([$selectedCatId]);
} else {
    $stmtProd = $pdo->query("
        SELECT p.*, c.name AS category_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.id ASC
    ");
}
$products = $stmtProd->fetchAll();

$siteName = get_setting('site_name', 'Royal Aura Luxury Jewellery');
$pageTitle = $siteName . ' • Haute Joaillerie Masterpieces';

require_once __DIR__ . '/includes/header.php';
?>

  <!-- Hero Section -->
  <section class="hero-banner">
    <div class="container hero-content">
      <div class="hero-tag">✦ IMPERIAL CRAFTSMANSHIP • BESPOKE LUXURY ✦</div>
      <h1 class="hero-title">
        Timeless Elegance & <br /><span class="gold-text">Royal Heritage</span>
      </h1>
      <p class="hero-desc">
        Explore our curated collection of 22K certified hallmarked gold, exquisite polki diamonds, temple harams, and handcrafted royal jewellery for your grandest moments.
      </p>
      <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="#products-section" class="btn btn-gold btn-lg">
          Explore Royal Catalogue
        </a>
        <a href="#categories-section" class="btn btn-outline-gold btn-lg">
          Browse Collections
        </a>
      </div>
    </div>
  </section>

  <!-- Categories Section -->
  <section id="categories-section" class="categories-section">
    <div class="container">
      <div class="section-title-wrap">
        <div class="section-subtitle">Exquisite Creations</div>
        <h2 class="section-title">Shop by Category</h2>
        <div class="gold-divider">
          <span class="diamond-gem"></span>
        </div>
      </div>

      <!-- Category Navigation Tabs -->
      <div class="category-nav-bar">
        <a 
          href="index.php#products-section" 
          class="category-tab-btn <?= $selectedCatId === 0 ? 'active' : '' ?>"
        >
          All Jewellery (<?= count($categories) > 0 ? '18+' : '0' ?>)
        </a>
        <?php foreach ($categories as $cat): ?>
          <a 
            href="index.php?category=<?= (int)$cat['id'] ?>#products-section" 
            class="category-tab-btn <?= $selectedCatId === (int)$cat['id'] ? 'active' : '' ?>"
          >
            <?= e($cat['name']) ?>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- Category Cards Grid -->
      <div class="category-cards-grid">
        <?php foreach ($categories as $cat): ?>
          <a href="index.php?category=<?= (int)$cat['id'] ?>#products-section" class="category-card">
            <div class="category-card-img-wrap">
              <img src="<?= e($cat['image']) ?>" alt="<?= e($cat['name']) ?>" loading="lazy" />
            </div>
            <div class="category-card-body">
              <h3 class="category-card-title"><?= e($cat['name']) ?></h3>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Products Section -->
  <section id="products-section" class="products-section">
    <div class="container">
      <div class="section-title-wrap">
        <div class="section-subtitle">Imperial Showcase</div>
        <h2 class="section-title">
          <?php 
            if ($selectedCatId > 0) {
              $currentCatName = 'Selected';
              foreach ($categories as $c) {
                if ((int)$c['id'] === $selectedCatId) { $currentCatName = $c['name']; break; }
              }
              echo e($currentCatName) . ' Masterpieces';
            } else {
              echo 'Handcrafted Luxury Jewellery';
            }
          ?>
        </h2>
        <div class="gold-divider">
          <span class="diamond-gem"></span>
        </div>
      </div>

      <!-- Product Cards Grid -->
      <?php if (empty($products)): ?>
        <div style="text-align: center; padding: 4rem 1rem; color: #cbd5e1;">
          <p style="font-size: 1.2rem; margin-bottom: 1.5rem;">No jewellery items currently found in this category.</p>
          <a href="index.php" class="btn btn-gold">View All Jewellery</a>
        </div>
      <?php else: ?>
        <div class="product-grid">
          <?php foreach ($products as $prod): 
            $priceFormatted = format_inr($prod['price_inr']);
          ?>
            <article class="product-card" id="product-card-<?= (int)$prod['id'] ?>">
              <!-- Jewellery Image Wrap with Smooth Hover Zoom -->
              <div class="product-image-wrap">
                <a href="product.php?id=<?= (int)$prod['id'] ?>">
                  <img src="<?= e($prod['image']) ?>" alt="<?= e($prod['name']) ?>" loading="lazy" />
                </a>
                <span class="product-badge-category"><?= e($prod['category_name']) ?></span>
                <span class="product-badge-model"><?= e($prod['model']) ?></span>
              </div>

              <!-- Product Details -->
              <div class="product-card-body">
                <a href="product.php?id=<?= (int)$prod['id'] ?>">
                  <h3 class="product-title"><?= e($prod['name']) ?></h3>
                </a>

                <div class="product-meta-row">
                  <span class="product-weight">
                    <span>⚖</span> <?= number_format($prod['weight_grams'], 2) ?> g (22K)
                  </span>
                  <span>Model: <?= e($prod['model']) ?></span>
                </div>

                <p class="product-details-snippet">
                  <?= e($prod['details']) ?>
                </p>

                <div class="product-price-row">
                  <span class="product-price-label">Price (INR)</span>
                  <span class="product-price-value"><?= $priceFormatted ?></span>
                </div>

                <!-- 2 Action Buttons Under Every Product -->
                <div class="product-actions-grid">
                  <!-- Buy Now Button -->
                  <button 
                    type="button" 
                    class="btn btn-buy-now btn-buy-now-trigger"
                    data-id="<?= (int)$prod['id'] ?>"
                    data-name="<?= e($prod['name']) ?>"
                    data-model="<?= e($prod['model']) ?>"
                    data-price="<?= e($priceFormatted) ?>"
                    data-image="<?= e($prod['image']) ?>"
                  >
                    <span>🛍 Buy Now</span>
                  </button>

                  <!-- Enquiry Button (Records in DB & Opens WhatsApp) -->
                  <button 
                    type="button" 
                    class="btn btn-enquiry btn-enquiry-trigger"
                    data-id="<?= (int)$prod['id'] ?>"
                    data-name="<?= e($prod['name']) ?>"
                    data-model="<?= e($prod['model']) ?>"
                    data-weight="<?= e($prod['weight_grams']) ?>"
                    data-price="<?= e($priceFormatted) ?>"
                    data-image="<?= e($prod['image']) ?>"
                  >
                    <span>💬 Enquiry</span>
                  </button>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

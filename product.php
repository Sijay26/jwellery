<?php
/**
 * Product Detail Page - Royal Aura Luxury Jewellery
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

require_customer_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$pdo = get_db_connection();

$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.id = ?
");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit;
}

// Fetch related items from the same category
$stmtRelated = $pdo->prepare("
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.category_id = ? AND p.id != ?
    LIMIT 3
");
$stmtRelated->execute([$product['category_id'], $id]);
$related = $stmtRelated->fetchAll();

$pageTitle = $product['name'] . ' • ' . get_setting('site_name');
$priceFormatted = format_inr($product['price_inr']);

require_once __DIR__ . '/includes/header.php';
?>

  <div class="container" style="padding-top: 3rem; padding-bottom: 5rem;">
    <!-- Breadcrumb -->
    <div style="font-size: 0.88rem; color: #cbd5e1; margin-bottom: 2rem;">
      <a href="index.php" style="color: var(--accent);">Home</a> 
      <span style="margin: 0 0.5rem; color: #64748b;">/</span> 
      <a href="index.php?category=<?= (int)$product['category_id'] ?>" style="color: var(--accent);">
        <?= e($product['category_name']) ?>
      </a> 
      <span style="margin: 0 0.5rem; color: #64748b;">/</span> 
      <span style="color: #ffffff;"><?= e($product['name']) ?></span>
    </div>

    <!-- Main Product Showcase Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 3.5rem; align-items: start;">
      <!-- Product Visual Showcase -->
      <div style="background: var(--card-glass); border: 1px solid var(--glass-border); border-radius: 24px; padding: 1.5rem; box-shadow: var(--shadow-lg), var(--shadow-purple); backdrop-filter: blur(20px);">
        <div style="width: 100%; border-radius: 16px; overflow: hidden; background: rgba(14,2,26,0.8);">
          <img src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>" style="width: 100%; height: auto;" />
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.25rem; padding: 0 0.5rem; font-size: 0.85rem; color: var(--accent-light);">
          <span>✦ BIS Hallmarked 22 Karat</span>
          <span>✦ 100% Conflict Free Diamond</span>
        </div>
      </div>

      <!-- Product Specifications & Actions -->
      <div style="background: var(--card-glass); border: 1px solid var(--glass-border); border-radius: 24px; padding: 2.5rem; box-shadow: var(--shadow-md); backdrop-filter: blur(20px);">
        <div style="display: flex; gap: 0.75rem; align-items: center; margin-bottom: 0.75rem;">
          <span class="user-badge" style="font-size: 0.8rem;"><?= e($product['category_name']) ?></span>
          <span style="font-size: 0.85rem; color: var(--text-dim);">Model: <?= e($product['model']) ?></span>
        </div>

        <h1 style="font-family: var(--font-serif); font-size: 2.4rem; color: #ffffff; line-height: 1.25; margin-bottom: 1.25rem;">
          <?= e($product['name']) ?>
        </h1>

        <!-- Price Display -->
        <div style="padding: 1rem 1.4rem; border-radius: 14px; background: rgba(255,255,255,0.04); border: 1px solid var(--glass-border); margin-bottom: 2rem; display: flex; align-items: baseline; justify-content: space-between;">
          <span style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-dim);">
            Price (All Taxes Incl.)
          </span>
          <span style="font-family: var(--font-sans); font-size: 2.2rem; font-weight: 700; color: var(--accent-light); text-shadow: 0 0 15px rgba(212,175,55,0.3);">
            <?= $priceFormatted ?>
          </span>
        </div>

        <!-- Specifications Table -->
        <div style="margin-bottom: 2rem;">
          <h4 style="font-family: var(--font-serif); font-size: 1.15rem; color: var(--accent); margin-bottom: 0.8rem;">
            Jewellery Attributes
          </h4>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; font-size: 0.9rem;">
            <div style="padding: 0.75rem; border-radius: 10px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
              <span style="color: var(--text-dim); display: block; font-size: 0.75rem;">GROSS WEIGHT</span>
              <strong style="color: #fff;">⚖ <?= number_format($product['weight_grams'], 2) ?> Grams</strong>
            </div>
            <div style="padding: 0.75rem; border-radius: 10px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
              <span style="color: var(--text-dim); display: block; font-size: 0.75rem;">PURITY / KARAT</span>
              <strong style="color: #fff;">22KT (916) Pure Gold</strong>
            </div>
            <div style="padding: 0.75rem; border-radius: 10px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
              <span style="color: var(--text-dim); display: block; font-size: 0.75rem;">AUTHENTICITY</span>
              <strong style="color: #fff;">HUID Hallmarked</strong>
            </div>
            <div style="padding: 0.75rem; border-radius: 10px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
              <span style="color: var(--text-dim); display: block; font-size: 0.75rem;">MODEL CODE</span>
              <strong style="color: #fff;"><?= e($product['model']) ?></strong>
            </div>
          </div>
        </div>

        <!-- Description -->
        <div style="margin-bottom: 2.5rem;">
          <h4 style="font-family: var(--font-serif); font-size: 1.15rem; color: var(--accent); margin-bottom: 0.6rem;">
            Artisan Description
          </h4>
          <p style="color: #cbd5e1; line-height: 1.8; font-size: 0.95rem;">
            <?= nl2br(e($product['details'])) ?>
          </p>
        </div>

        <!-- Action Buttons -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <button 
            type="button" 
            class="btn btn-gold btn-lg btn-buy-now-trigger"
            data-id="<?= (int)$product['id'] ?>"
            data-name="<?= e($product['name']) ?>"
            data-model="<?= e($product['model']) ?>"
            data-price="<?= e($priceFormatted) ?>"
            data-image="<?= e($product['image']) ?>"
          >
            <span>🛍 Buy Now</span>
          </button>

          <button 
            type="button" 
            class="btn btn-enquiry btn-lg btn-enquiry-trigger"
            data-id="<?= (int)$product['id'] ?>"
            data-name="<?= e($product['name']) ?>"
            data-model="<?= e($product['model']) ?>"
            data-weight="<?= e($product['weight_grams']) ?>"
            data-price="<?= e($priceFormatted) ?>"
            data-image="<?= e($product['image']) ?>"
          >
            <span>💬 WhatsApp Enquiry</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Related Masterpieces -->
    <?php if (!empty($related)): ?>
      <div style="margin-top: 5rem;">
        <div class="section-title-wrap">
          <div class="section-subtitle">You May Also Admire</div>
          <h3 class="section-title" style="font-size: 2rem;">Complementary Royal Creations</h3>
        </div>
        <div class="product-grid">
          <?php foreach ($related as $rel): 
            $relPrice = format_inr($rel['price_inr']);
          ?>
            <article class="product-card">
              <div class="product-image-wrap">
                <a href="product.php?id=<?= (int)$rel['id'] ?>">
                  <img src="<?= e($rel['image']) ?>" alt="<?= e($rel['name']) ?>" loading="lazy" />
                </a>
                <span class="product-badge-category"><?= e($rel['category_name']) ?></span>
                <span class="product-badge-model"><?= e($rel['model']) ?></span>
              </div>
              <div class="product-card-body">
                <a href="product.php?id=<?= (int)$rel['id'] ?>">
                  <h4 class="product-title"><?= e($rel['name']) ?></h4>
                </a>
                <div class="product-price-row">
                  <span class="product-price-label">Price</span>
                  <span class="product-price-value"><?= $relPrice ?></span>
                </div>
                <div class="product-actions-grid">
                  <button 
                    type="button" 
                    class="btn btn-buy-now btn-buy-now-trigger"
                    data-id="<?= (int)$rel['id'] ?>"
                    data-name="<?= e($rel['name']) ?>"
                    data-model="<?= e($rel['model']) ?>"
                    data-price="<?= e($relPrice) ?>"
                    data-image="<?= e($rel['image']) ?>"
                  >
                    <span>🛍 Buy Now</span>
                  </button>
                  <button 
                    type="button" 
                    class="btn btn-enquiry btn-enquiry-trigger"
                    data-id="<?= (int)$rel['id'] ?>"
                    data-name="<?= e($rel['name']) ?>"
                    data-model="<?= e($rel['model']) ?>"
                    data-weight="<?= e($rel['weight_grams']) ?>"
                    data-price="<?= e($relPrice) ?>"
                    data-image="<?= e($rel['image']) ?>"
                  >
                    <span>💬 Enquiry</span>
                  </button>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

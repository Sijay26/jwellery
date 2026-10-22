<?php
/**
 * Customer Footer Component & Buy Now Modal
 * Royal Aura Luxury Jewellery
 */

$waNumber = get_setting('whatsapp_number', '6369058169');
$currentUser = get_current_user_data();
?>
  <!-- Buy Now Modal with Instant Red-Border Validation -->
  <div id="buy-now-modal" class="modal-overlay">
    <div class="modal-content">
      <button type="button" class="modal-close-btn" aria-label="Close modal">✕</button>

      <div style="text-align: center; margin-bottom: 1.5rem;">
        <span class="hero-tag" style="margin-bottom: 0.5rem;">PURCHASE CONCIERGE</span>
        <h3 class="luxury-heading" style="font-size: 1.6rem;">Complete Your Order</h3>
      </div>

      <!-- Selected Product Preview -->
      <div class="modal-product-summary">
        <img id="modal-summary-img" src="assets/images/logo.svg" alt="Jewellery" class="modal-product-thumb" />
        <div class="modal-product-info">
          <h4 id="modal-summary-title">Selected Jewellery</h4>
          <div id="modal-summary-model" style="font-size: 0.8rem; color: #cbd5e1;">Model: ---</div>
          <div id="modal-summary-price" class="modal-price">₹0</div>
        </div>
      </div>

      <!-- Purchase Form -->
      <form id="buy-now-form" autocomplete="off" novalidate>
        <input type="hidden" id="buy-product-id" name="product_id" value="" />
        <?php csrf_input(); ?>

        <div class="form-group">
          <label class="form-label" for="buy-customer-name">Full Name *</label>
          <input 
            type="text" 
            id="buy-customer-name" 
            name="customer_name" 
            class="form-control" 
            placeholder="e.g. Maharani Ananya Sharma" 
            required
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="buy-customer-phone">10-Digit Mobile Number *</label>
          <input 
            type="tel" 
            id="buy-customer-phone" 
            name="phone" 
            class="form-control" 
            placeholder="e.g. 9876543210" 
            maxlength="10" 
            value="<?= e($currentUser['phone'] ?? '') ?>"
            required
          />
        </div>

        <div class="form-group">
          <label class="form-label" for="buy-customer-address">Complete Delivery Address with Pincode *</label>
          <textarea 
            id="buy-customer-address" 
            name="address" 
            class="form-control" 
            rows="3" 
            placeholder="House/Villa No., Royal Enclave, Street, City, State - 600001"
            required
          ></textarea>
        </div>

        <button type="submit" class="btn btn-gold" style="width: 100%; padding: 0.95rem; font-size: 1rem;">
          <span>✦ Confirm Order & Reserve Jewellery</span>
        </button>
      </form>
    </div>
  </div>

  <!-- Luxury Footer -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
          <?php $footerLogo = get_setting('logo', 'assets/images/logo.png'); ?>
          <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.2rem;">
            <img src="<?= e($footerLogo) ?>" alt="Royal Aura" style="height: 48px; width: auto; max-width: 140px; object-fit: contain; border-radius: 8px;" />
            <?php if (!str_ends_with(strtolower($footerLogo), '.svg')): ?>
              <div>
                <div style="font-family: var(--font-serif); font-size: 1.25rem; font-weight: 700; background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"><?= e(get_setting('site_name', 'Royal Aura Luxury Jewellery')) ?></div>
                <div style="font-size: 0.6rem; letter-spacing: 3px; color: var(--accent-light);">HAUTE JOAILLERIE</div>
              </div>
            <?php endif; ?>
          </div>
          <p style="color: #cbd5e1; font-size: 0.9rem; line-height: 1.7; margin-bottom: 1.5rem;">
            Royal Aura represents the zenith of Indian and international haute joaillerie. Each masterpiece is handcrafted in 22K/18K BIS hallmarked gold and certified conflict-free diamonds.
          </p>
          <div style="display: flex; gap: 0.75rem;">
            <span class="user-badge" style="font-size: 0.75rem;">✨ 100% BIS Hallmarked</span>
            <span class="user-badge" style="font-size: 0.75rem;">💎 IGI & GIA Certified</span>
          </div>
        </div>

        <div class="footer-col">
          <h4>Royal Collections</h4>
          <ul class="footer-links">
            <li><a href="index.php?category=1">Solitaire Rings</a></li>
            <li><a href="index.php?category=2">Heritage Harams</a></li>
            <li><a href="index.php?category=3">Diamond Bracelets</a></li>
            <li><a href="index.php?category=4">Solid Gold Chains</a></li>
            <li><a href="index.php?category=5">Kada Bangles</a></li>
            <li><a href="index.php?category=6">Peacock Earrings</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Client Concierge</h4>
          <ul class="footer-links">
            <li><a href="login.php">Customer Portal</a></li>
            <li><a href="login.php?role=admin">Admin Access</a></li>
            <li><a href="https://wa.me/91<?= e($waNumber) ?>" target="_blank">Direct WhatsApp Desk</a></li>
            <li><a href="#">Certificate Authentication</a></li>
            <li><a href="#">Bespoke Bridal Design</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Bespoke Assistance</h4>
          <p style="color: #cbd5e1; font-size: 0.88rem; margin-bottom: 1rem;">
            Connect directly with our master jewellers for real-time video shopping or valuation.
          </p>
          <a 
            href="https://wa.me/91<?= e($waNumber) ?>?text=Hello%20Royal%20Aura%2C%20I%20would%20like%20a%20personal%20jewellery%20consultation" 
            target="_blank" 
            class="btn btn-outline-gold"
            style="width: 100%;"
          >
            💬 WhatsApp: +91 <?= e($waNumber) ?>
          </a>
        </div>
      </div>

      <div class="footer-bottom">
        <p>© <?= date('Y') ?> <?= e(get_setting('site_name', 'Royal Aura Luxury Jewellery')) ?>. All Rights Reserved. Crafted for Royalty.</p>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="assets/js/main.js"></script>
</body>
</html>

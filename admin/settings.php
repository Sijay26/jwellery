<?php
/**
 * Theme & General Settings - Royal Aura Luxury Jewellery
 * Change website theme color with interactive color picker & presets, upload new logo, update site name & WhatsApp
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$adminTitle = 'Theme & General Settings';
$pdo = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Theme Color
    $themeColor = trim($_POST['theme_color'] ?? '');
    if (!empty($themeColor) && preg_match('/^#[a-fA-F0-9]{6}$/', $themeColor)) {
        update_setting('theme_color', strtolower($themeColor));
    }

    // 2. Site Name
    $siteName = trim($_POST['site_name'] ?? '');
    if (!empty($siteName)) {
        update_setting('site_name', $siteName);
    }

    // 3. WhatsApp Number
    $whatsapp = trim($_POST['whatsapp_number'] ?? '');
    if (!empty($whatsapp)) {
        // Remove non-numeric
        $cleanWa = preg_replace('/[^0-9]/', '', $whatsapp);
        update_setting('whatsapp_number', $cleanWa);
    }

    // 4. Logo Upload
    if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
        $uploadedLogo = handle_image_upload($_FILES['logo_file'], 'uploads/');
        if ($uploadedLogo) {
            update_setting('logo', $uploadedLogo);
        } else {
            set_flash('error', 'Invalid logo file format. Upload SVG, PNG, or JPG.');
        }
    }

    set_flash('success', 'Website theme color, logo, and settings were successfully updated across the entire site!');
    header('Location: settings.php');
    exit;
}

$currentTheme = get_setting('theme_color', '#4b1d8f');
$currentLogo = get_setting('logo', 'assets/images/logo.png');
$currentSiteName = get_setting('site_name', 'Royal Aura Luxury Jewellery');
$currentWhatsApp = get_setting('whatsapp_number', '6369058169');

$logoDisplayPath = str_starts_with($currentLogo, 'http') ? $currentLogo : '../' . $currentLogo;

require_once __DIR__ . '/header.php';
?>

  <!-- Theme Color Customization Card -->
  <div class="admin-card">
    <div class="admin-card-header">
      <h3 class="admin-card-title">✦ Royal Website Theme Color Customizer</h3>
    </div>
    <p style="color: #cbd5e1; font-size: 0.92rem; margin-bottom: 1.5rem; line-height: 1.6;">
      Select any royal base color below. The system automatically recalculates matching dark and light gradient stops, glass glows, and accents across both the customer site and administrative portal.
    </p>

    <form action="settings.php" method="POST" enctype="multipart/form-data" autocomplete="off">
      <?php csrf_input(); ?>

      <div class="theme-picker-box">
        <!-- Native Color Picker Wheel -->
        <input 
          type="color" 
          id="theme-color-input" 
          name="theme_color" 
          value="<?= e($currentTheme) ?>" 
          class="color-input-custom" 
          title="Click to pick a custom luxury color"
        />

        <div style="flex: 1;">
          <label class="form-label">Theme Hex Code</label>
          <input 
            type="text" 
            id="theme-hex-input" 
            class="form-control" 
            style="max-width: 200px; font-weight: 700; text-transform: uppercase;" 
            value="<?= e($currentTheme) ?>" 
          />
          <small style="color: var(--text-dim); font-size: 0.8rem; margin-top: 0.35rem; display: block;">
            Drag the color box or choose a royal preset below to preview instantly.
          </small>
        </div>
      </div>

      <!-- Luxury Color Presets -->
      <div style="margin-bottom: 1.5rem;">
        <label class="form-label" style="margin-bottom: 0.75rem;">Curated Imperial Presets:</label>
        <div class="color-presets-row">
          <div class="preset-chip" data-color="#4b1d8f">
            <span class="preset-dot" style="background: #4b1d8f;"></span>
            <span>Royal Purple (Default)</span>
          </div>
          <div class="preset-chip" data-color="#064e3b">
            <span class="preset-dot" style="background: #064e3b;"></span>
            <span>Emerald Royalty</span>
          </div>
          <div class="preset-chip" data-color="#1e3a8a">
            <span class="preset-dot" style="background: #1e3a8a;"></span>
            <span>Sapphire Night</span>
          </div>
          <div class="preset-chip" data-color="#831843">
            <span class="preset-dot" style="background: #831843;"></span>
            <span>Ruby Wine</span>
          </div>
          <div class="preset-chip" data-color="#18181b">
            <span class="preset-dot" style="background: #18181b;"></span>
            <span>Midnight Obsidian</span>
          </div>
          <div class="preset-chip" data-color="#7c2d12">
            <span class="preset-dot" style="background: #7c2d12;"></span>
            <span>Antique Copper</span>
          </div>
        </div>
      </div>

      <!-- Real-time Gradient Preview Bar -->
      <label class="form-label">Auto-Generated Gradient Preview:</label>
      <div class="gradient-preview-bar" id="preview-gradient-bar">
        Imperial Luxury Gradient
      </div>

      <div class="gold-divider" style="margin: 2.5rem 0;">
        <span class="diamond-gem"></span>
      </div>

      <!-- Logo & General Brand Settings -->
      <div class="admin-card-header">
        <h3 class="admin-card-title">✦ Brand Identity & Logo Settings</h3>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="form-group">
          <label class="form-label">Site / Brand Name *</label>
          <input 
            type="text" 
            name="site_name" 
            class="form-control" 
            value="<?= e($currentSiteName) ?>" 
            required
          />
        </div>

        <div class="form-group">
          <label class="form-label">Customer WhatsApp Number *</label>
          <input 
            type="text" 
            name="whatsapp_number" 
            class="form-control" 
            value="<?= e($currentWhatsApp) ?>" 
            placeholder="6369058169" 
            required
          />
          <small style="color: var(--text-dim); font-size: 0.78rem;">Used for direct customer enquiries and concierge messaging.</small>
        </div>

        <div class="form-group">
          <label class="form-label">Upload New Brand Logo</label>
          <input 
            type="file" 
            name="logo_file" 
            class="form-control image-upload-input" 
            data-preview="logo-img-preview"
            accept="image/png, image/jpeg, image/webp, image/svg+xml"
          />
          <small style="color: var(--text-dim); font-size: 0.78rem;">Upload SVG or high-res transparent PNG.</small>
          <div style="margin-top: 0.75rem; display: flex; align-items: center; gap: 1rem;">
            <span style="font-size: 0.8rem; color: var(--accent);">Active Logo:</span>
            <img 
              id="logo-img-preview"
              src="<?= e($logoDisplayPath) ?>" 
              alt="Logo" 
              style="height: 48px; border-radius: 8px; border: 1px solid var(--glass-border); padding: 4px; background: rgba(0,0,0,0.5);"
            />
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-gold btn-lg">
        <span>✦ Save All Theme & Identity Settings</span>
      </button>
    </form>
  </div>

<?php require_once __DIR__ . '/footer.php'; ?>

<?php
/**
 * Global Helper Functions
 * Royal Aura Luxury Jewellery
 */

require_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    // Configure session security parameters
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

/**
 * HTML Escaping helper
 * @param string|null $str
 * @return string
 */
function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

/**
 * Format currency in Indian Numbering System (e.g., ₹1,25,000)
 * @param float|int|string $amount
 * @return string
 */
function format_inr($amount) {
    $amount = (float)$amount;
    $isNegative = $amount < 0;
    $amount = abs($amount);

    $formattedNumber = number_format($amount, 2, '.', '');
    list($intPart, $decPart) = explode('.', $formattedNumber);

    $len = strlen($intPart);
    if ($len <= 3) {
        $result = $intPart;
    } else {
        $lastThree = substr($intPart, -3);
        $remaining = substr($intPart, 0, -3);
        // Add commas every 2 digits for remaining part
        $remaining = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $remaining);
        $result = $remaining . ',' . $lastThree;
    }

    $formatted = '₹' . ($isNegative ? '-' : '') . $result;
    return $formatted;
}

/**
 * Generate or get CSRF token
 * @return string
 */
function get_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output hidden CSRF input field
 * @return void
 */
function csrf_input() {
    echo '<input type="hidden" name="csrf_token" value="' . e(get_csrf_token()) . '">';
}

/**
 * Verify CSRF token
 * @param string|null $token
 * @return bool
 */
function verify_csrf_token($token) {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Set flash message
 * @param string $type success|error|info
 * @param string $message
 */
function set_flash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 * @return array|null
 */
function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Convert Hex color to RGB
 * @param string $hex
 * @return array [r, g, b]
 */
function hex_to_rgb($hex) {
    $hex = ltrim($hex, '#');
    if (strlen($hex) == 3) {
        $hex = $hex[0].$hex[0] . $hex[1].$hex[1] . $hex[2].$hex[2];
    }
    if (strlen($hex) != 6) {
        return [75, 29, 143]; // Default royal purple
    }
    return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2))
    ];
}

/**
 * Adjust hex color brightness
 * @param string $hex
 * @param int $percent (-100 to 100)
 * @return string
 */
function adjust_brightness($hex, $percent) {
    $rgb = hex_to_rgb($hex);
    foreach ($rgb as &$color) {
        $adjustment = ($percent > 0) ? (255 - $color) * ($percent / 100) : $color * ($percent / 100);
        $color = max(0, min(255, round($color + $adjustment)));
    }
    return sprintf('#%02x%02x%02x', $rgb[0], $rgb[1], $rgb[2]);
}

/**
 * Render dynamic theme CSS variables based on DB settings
 */
function render_theme_styles() {
    $baseTheme = get_setting('theme_color', '#4b1d8f');
    $rgb = hex_to_rgb($baseTheme);
    $darkShade = adjust_brightness($baseTheme, -65);   // ~ #1a0533
    $lightShade = adjust_brightness($baseTheme, 45);   // ~ #7b2ff7
    $ultraDark = adjust_brightness($baseTheme, -85);   // deep obsidian base

    ?>
    <style id="dynamic-theme-vars">
      :root {
        --primary: <?= e($baseTheme) ?>;
        --primary-dark: <?= e($darkShade) ?>;
        --primary-light: <?= e($lightShade) ?>;
        --primary-rgb: <?= $rgb[0] ?>, <?= $rgb[1] ?>, <?= $rgb[2] ?>;
        --primary-glow: rgba(<?= $rgb[0] ?>, <?= $rgb[1] ?>, <?= $rgb[2] ?>, 0.45);
        --primary-subtle: rgba(<?= $rgb[0] ?>, <?= $rgb[1] ?>, <?= $rgb[2] ?>, 0.12);
        --primary-border: rgba(<?= $rgb[0] ?>, <?= $rgb[1] ?>, <?= $rgb[2] ?>, 0.35);

        --accent: #d4af37;
        --accent-light: #f7e7a8;
        --accent-dark: #aa771c;
        --accent-glow: rgba(212, 175, 55, 0.4);

        --bg-obsidian: <?= e($ultraDark) ?>;
        --gradient-royal: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, var(--primary-light) 100%);
        --gradient-gold: linear-gradient(135deg, #d4af37 0%, #f7e7a8 50%, #aa771c 100%);
        --gradient-gold-hover: linear-gradient(135deg, #f7e7a8 0%, #d4af37 50%, #8a5e12 100%);
        --gradient-glass: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.02) 100%);
      }
    </style>
    <?php
}

/**
 * Handle secure image upload
 * @param array $file $_FILES['...']
 * @param string $targetDir
 * @return string|false Relative path on success, false on failure
 */
function handle_image_upload($file, $targetDir = 'uploads/') {
    if (!isset($file['error']) || is_array($file['error'])) {
        return false;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    // Check size (Max 6MB)
    if ($file['size'] > 6 * 1024 * 1024) {
        return false;
    }

    // Allowed MIME types
    $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/svg+xml' => 'svg'
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if (!array_key_exists($mime, $allowedMimes)) {
        return false;
    }

    $ext = $allowedMimes[$mime];
    $filename = sprintf('img_%s_%s.%s', date('Ymd_His'), bin2hex(random_bytes(6)), $ext);

    $absoluteTargetDir = dirname(__DIR__) . '/' . trim($targetDir, '/') . '/';
    if (!is_dir($absoluteTargetDir)) {
        mkdir($absoluteTargetDir, 0777, true);
    }

    $destination = $absoluteTargetDir . $filename;
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return trim($targetDir, '/') . '/' . $filename;
    }

    return false;
}

/**
 * Royal Aura Luxury Jewellery - Admin JavaScript
 * Real-time Theme Color preview, image upload preview, order status AJAX
 */

document.addEventListener('DOMContentLoaded', function () {
  initThemeColorPicker();
  initImagePreviews();
  initDeleteConfirmations();
});

/**
 * 1. LIVE THEME COLOR PICKER & PRESETS
 */
function initThemeColorPicker() {
  const colorInput = document.getElementById('theme-color-input');
  const hexTextInput = document.getElementById('theme-hex-input');
  const gradientBar = document.getElementById('preview-gradient-bar');
  const presetChips = document.querySelectorAll('.preset-chip');

  if (!colorInput) return;

  function applyColor(hex) {
    hex = hex.trim();
    if (!hex.startsWith('#')) hex = '#' + hex;
    colorInput.value = hex;
    if (hexTextInput) hexTextInput.value = hex;

    // Derive shades
    const rgb = hexToRgb(hex);
    if (!rgb) return;

    const dark = adjustBrightness(hex, -65);
    const light = adjustBrightness(hex, 45);

    // Update dynamic root styles
    document.documentElement.style.setProperty('--primary', hex);
    document.documentElement.style.setProperty('--primary-dark', dark);
    document.documentElement.style.setProperty('--primary-light', light);
    document.documentElement.style.setProperty('--primary-rgb', `${rgb.r}, ${rgb.g}, ${rgb.b}`);
    document.documentElement.style.setProperty('--primary-glow', `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, 0.45)`);
    document.documentElement.style.setProperty('--gradient-royal', `linear-gradient(135deg, ${dark} 0%, ${hex} 50%, ${light} 100%)`);

    if (gradientBar) {
      gradientBar.style.background = `linear-gradient(135deg, ${dark} 0%, ${hex} 50%, ${light} 100%)`;
      gradientBar.textContent = `Generated Gradient: ${dark} ➔ ${hex} ➔ ${light}`;
    }
  }

  colorInput.addEventListener('input', function () {
    applyColor(this.value);
  });

  if (hexTextInput) {
    hexTextInput.addEventListener('input', function () {
      if (/^#?[0-9A-Fa-f]{6}$/.test(this.value)) {
        applyColor(this.value);
      }
    });
  }

  presetChips.forEach(chip => {
    chip.addEventListener('click', function () {
      const color = this.getAttribute('data-color');
      if (color) applyColor(color);
    });
  });
}

function hexToRgb(hex) {
  hex = hex.replace(/^#/, '');
  if (hex.length === 3) hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
  if (hex.length !== 6) return null;
  const num = parseInt(hex, 16);
  return {
    r: (num >> 16) & 255,
    g: (num >> 8) & 255,
    b: num & 255
  };
}

function adjustBrightness(hex, percent) {
  const rgb = hexToRgb(hex);
  if (!rgb) return hex;
  const adjust = (c) => {
    const val = (percent > 0) ? (255 - c) * (percent / 100) : c * (percent / 100);
    return Math.max(0, Math.min(255, Math.round(c + val)));
  };
  const r = adjust(rgb.r).toString(16).padStart(2, '0');
  const g = adjust(rgb.g).toString(16).padStart(2, '0');
  const b = adjust(rgb.b).toString(16).padStart(2, '0');
  return `#${r}${g}${b}`;
}

/**
 * 2. IMAGE UPLOAD PREVIEW
 */
function initImagePreviews() {
  const fileInputs = document.querySelectorAll('.image-upload-input');
  fileInputs.forEach(input => {
    input.addEventListener('change', function () {
      const previewId = this.getAttribute('data-preview');
      const previewEl = document.getElementById(previewId);
      if (this.files && this.files[0] && previewEl) {
        const reader = new FileReader();
        reader.onload = function (e) {
          previewEl.src = e.target.result;
          previewEl.style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  });
}

/**
 * 3. DELETE CONFIRMATION
 */
function initDeleteConfirmations() {
  document.querySelectorAll('.btn-confirm-delete').forEach(btn => {
    btn.addEventListener('click', function (e) {
      const itemTitle = this.getAttribute('data-title') || 'this item';
      if (!confirm(`Are you sure you want to permanently delete "${itemTitle}"? This action cannot be undone.`)) {
        e.preventDefault();
      }
    });
  });
}

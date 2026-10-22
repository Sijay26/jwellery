<?php
/**
 * Asset Generator for Royal Aura Luxury Jewellery
 * Generates beautiful, responsive luxury SVGs for categories and products.
 */

$assetsDir = __DIR__ . '/assets/images';
if (!is_dir($assetsDir)) {
    mkdir($assetsDir, 0777, true);
}

// Function to generate category SVGs
function generateCategorySVG($name, $subtitle, $iconType, $accentColor = '#d4af37') {
    $goldDef = '
    <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#bf953f"/>
      <stop offset="25%" stop-color="#fcf6ba"/>
      <stop offset="50%" stop-color="#b38728"/>
      <stop offset="75%" stop-color="#fbf5b7"/>
      <stop offset="100%" stop-color="#aa771c"/>
    </linearGradient>
    <radialGradient id="cardGlow" cx="50%" cy="40%" r="60%">
      <stop offset="0%" stop-color="#7b2ff7" stop-opacity="0.45"/>
      <stop offset="60%" stop-color="#4b1d8f" stop-opacity="0.25"/>
      <stop offset="100%" stop-color="#140224" stop-opacity="0.95"/>
    </radialGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="8" stdDeviation="6" flood-color="#000000" flood-opacity="0.6"/>
    </filter>
    <filter id="softGlow">
      <feGaussianBlur stdDeviation="4" result="coloredBlur"/>
      <feMerge>
        <feMergeNode in="coloredBlur"/>
        <feMergeNode in="SourceGraphic"/>
      </feMerge>
    </filter>';

    $iconSvg = '';
    switch ($iconType) {
        case 'ring':
            $iconSvg = '
            <!-- Solitaire Ring -->
            <circle cx="200" cy="180" r="70" fill="none" stroke="url(#gold)" stroke-width="14" filter="url(#shadow)"/>
            <circle cx="200" cy="180" r="58" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
            <!-- Diamond mount -->
            <path d="M185,110 L215,110 L208,90 L192,90 Z" fill="url(#gold)"/>
            <!-- Brilliant Diamond -->
            <polygon points="200,60 225,85 212,105 188,105 175,85" fill="#f8fafc" stroke="#94a3b8" stroke-width="1.5" filter="url(#softGlow)"/>
            <polygon points="188,85 200,60 212,85 200,105" fill="#e2e8f0" opacity="0.8"/>
            <!-- Sparkles -->
            <polygon points="160,70 163,78 171,81 163,84 160,92 157,84 149,81 157,78" fill="#ffffff"/>
            <polygon points="235,90 237,95 242,97 237,99 235,104 233,99 228,97 233,95" fill="#fef08a"/>
            ';
            break;

        case 'haram':
            $iconSvg = '
            <!-- Grand Temple Haram -->
            <path d="M120,90 Q200,230 280,90" fill="none" stroke="url(#gold)" stroke-width="12" filter="url(#shadow)"/>
            <path d="M135,100 Q200,215 265,100" fill="none" stroke="url(#gold)" stroke-width="6"/>
            <!-- Hanging pearls/beads -->
            <circle cx="160" cy="155" r="8" fill="#fef9c3"/>
            <circle cx="180" cy="185" r="9" fill="#fef9c3"/>
            <circle cx="200" cy="200" r="10" fill="#fef9c3"/>
            <circle cx="220" cy="185" r="9" fill="#fef9c3"/>
            <circle cx="240" cy="155" r="8" fill="#fef9c3"/>
            <!-- Temple Pendant Motif -->
            <path d="M175,200 L225,200 L220,245 L200,265 L180,245 Z" fill="url(#gold)" filter="url(#shadow)"/>
            <!-- Center Ruby -->
            <ellipse cx="200" cy="225" rx="14" ry="18" fill="#e11d48" stroke="url(#gold)" stroke-width="3" filter="url(#softGlow)"/>
            <circle cx="200" cy="275" r="7" fill="#fef9c3"/>
            ';
            break;

        case 'bracelet':
            $iconSvg = '
            <!-- Luxury Bracelet / Bangle Cuff -->
            <ellipse cx="200" cy="170" rx="90" ry="50" fill="none" stroke="url(#gold)" stroke-width="16" filter="url(#shadow)"/>
            <ellipse cx="200" cy="170" rx="76" ry="40" fill="none" stroke="#7b2ff7" stroke-width="3"/>
            <!-- Diamond & Gem Insets along circumference -->
            <circle cx="140" cy="190" r="5" fill="#ffffff" filter="url(#softGlow)"/>
            <circle cx="170" cy="205" r="6" fill="#10b981" stroke="url(#gold)" stroke-width="1.5"/>
            <circle cx="200" cy="210" r="7" fill="#ffffff" filter="url(#softGlow)"/>
            <circle cx="230" cy="205" r="6" fill="#10b981" stroke="url(#gold)" stroke-width="1.5"/>
            <circle cx="260" cy="190" r="5" fill="#ffffff" filter="url(#softGlow)"/>
            <!-- Sparkles -->
            <polygon points="120,150 123,156 129,158 123,160 120,166 117,160 111,158 117,156" fill="#ffffff"/>
            ';
            break;

        case 'chain':
            $iconSvg = '
            <!-- Luxury Interlocking Gold Chain -->
            <g transform="translate(40, -10)" filter="url(#shadow)">
              <!-- Link 1 -->
              <rect x="130" y="80" width="60" height="35" rx="16" fill="none" stroke="url(#gold)" stroke-width="10" transform="rotate(-25 160 100)"/>
              <!-- Link 2 -->
              <rect x="150" y="120" width="60" height="35" rx="16" fill="none" stroke="url(#gold)" stroke-width="10" transform="rotate(25 180 140)"/>
              <!-- Link 3 -->
              <rect x="130" y="160" width="60" height="35" rx="16" fill="none" stroke="url(#gold)" stroke-width="10" transform="rotate(-25 160 180)"/>
              <!-- Link 4 -->
              <rect x="150" y="200" width="60" height="35" rx="16" fill="none" stroke="url(#gold)" stroke-width="10" transform="rotate(25 180 220)"/>
            </g>
            <polygon points="260,140 263,148 271,151 263,154 260,162 257,154 249,151 257,148" fill="#fef08a"/>
            ';
            break;

        case 'bangles':
            $iconSvg = '
            <!-- Pair of Royal Kada Bangles -->
            <ellipse cx="180" cy="160" rx="75" ry="45" fill="none" stroke="url(#gold)" stroke-width="14" filter="url(#shadow)"/>
            <ellipse cx="220" cy="180" rx="75" ry="45" fill="none" stroke="url(#gold)" stroke-width="14" filter="url(#shadow)"/>
            <!-- Ruby & Kundan Details -->
            <circle cx="160" cy="190" r="5" fill="#e11d48"/>
            <circle cx="180" cy="195" r="5" fill="#10b981"/>
            <circle cx="200" cy="192" r="5" fill="#e11d48"/>
            <circle cx="220" cy="215" r="5" fill="#e11d48"/>
            <circle cx="240" cy="218" r="5" fill="#10b981"/>
            <circle cx="260" cy="212" r="5" fill="#e11d48"/>
            <polygon points="130,110 133,117 140,119 133,121 130,128 127,121 120,119 127,117" fill="#ffffff"/>
            ';
            break;

        case 'earrings':
            $iconSvg = '
            <!-- Royal Peacock Jhumkas -->
            <g transform="translate(-45, 0)">
              <!-- Stud top -->
              <circle cx="200" cy="95" r="14" fill="#e11d48" stroke="url(#gold)" stroke-width="4" filter="url(#softGlow)"/>
              <!-- Connecting stem -->
              <line x1="200" y1="110" x2="200" y2="135" stroke="url(#gold)" stroke-width="4"/>
              <!-- Jhumka dome -->
              <path d="M175,155 C175,130 225,130 225,155 Z" fill="url(#gold)" filter="url(#shadow)"/>
              <line x1="172" y1="156" x2="228" y2="156" stroke="url(#gold)" stroke-width="4"/>
              <!-- Tiny hanging beads -->
              <circle cx="180" cy="166" r="3" fill="#fef9c3"/>
              <circle cx="190" cy="168" r="3" fill="#fef9c3"/>
              <circle cx="200" cy="170" r="3.5" fill="#fef9c3"/>
              <circle cx="210" cy="168" r="3" fill="#fef9c3"/>
              <circle cx="220" cy="166" r="3" fill="#fef9c3"/>
            </g>
            <g transform="translate(45, 10)">
              <!-- Stud top -->
              <circle cx="200" cy="95" r="14" fill="#e11d48" stroke="url(#gold)" stroke-width="4" filter="url(#softGlow)"/>
              <line x1="200" y1="110" x2="200" y2="135" stroke="url(#gold)" stroke-width="4"/>
              <path d="M175,155 C175,130 225,130 225,155 Z" fill="url(#gold)" filter="url(#shadow)"/>
              <line x1="172" y1="156" x2="228" y2="156" stroke="url(#gold)" stroke-width="4"/>
              <circle cx="180" cy="166" r="3" fill="#fef9c3"/>
              <circle cx="190" cy="168" r="3" fill="#fef9c3"/>
              <circle cx="200" cy="170" r="3.5" fill="#fef9c3"/>
              <circle cx="210" cy="168" r="3" fill="#fef9c3"/>
              <circle cx="220" cy="166" r="3" fill="#fef9c3"/>
            </g>
            ';
            break;
    }

    return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 320" width="400" height="320">
  <defs>
    $goldDef
  </defs>
  <!-- Card Background -->
  <rect width="400" height="320" rx="20" fill="url(#cardGlow)"/>
  <rect x="2" y="2" width="396" height="316" rx="18" fill="none" stroke="url(#gold)" stroke-width="1.2" stroke-opacity="0.5"/>
  
  <!-- Subtle corner filigree -->
  <path d="M10,35 C10,18 18,10 35,10" fill="none" stroke="url(#gold)" stroke-width="1.5" opacity="0.4"/>
  <path d="M390,35 C390,18 382,10 365,10" fill="none" stroke="url(#gold)" stroke-width="1.5" opacity="0.4"/>
  
  $iconSvg

  <!-- Category Title Banner -->
  <rect x="50" y="255" width="300" height="45" rx="10" fill="rgba(20, 2, 36, 0.75)" stroke="url(#gold)" stroke-width="1" filter="url(#shadow)"/>
  <text x="200" y="280" font-family="'Playfair Display', Georgia, serif" font-size="20" font-weight="700" fill="url(#gold)" text-anchor="middle" letter-spacing="2">
    $name
  </text>
  <text x="200" y="293" font-family="'Poppins', sans-serif" font-size="9" font-weight="500" fill="#e0c77a" text-anchor="middle" letter-spacing="3">
    $subtitle
  </text>
</svg>
SVG;
}

// Function to generate Product SVGs
function generateProductSVG($name, $model, $category, $gemColor, $styleVariant, $weight) {
    $goldDef = '
    <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#d4af37"/>
      <stop offset="25%" stop-color="#fff5cc"/>
      <stop offset="50%" stop-color="#aa771c"/>
      <stop offset="75%" stop-color="#fdf1a9"/>
      <stop offset="100%" stop-color="#8a5e12"/>
    </linearGradient>
    <radialGradient id="productBg" cx="50%" cy="45%" r="70%">
      <stop offset="0%" stop-color="#5a23a8" stop-opacity="0.35"/>
      <stop offset="55%" stop-color="#2a084e" stop-opacity="0.7"/>
      <stop offset="100%" stop-color="#0e011a" stop-opacity="0.98"/>
    </radialGradient>
    <filter id="pShadow">
      <feDropShadow dx="0" dy="12" stdDeviation="8" flood-color="#000000" flood-opacity="0.75"/>
    </filter>
    <filter id="gemGlow">
      <feGaussianBlur stdDeviation="5" result="blur"/>
      <feMerge>
        <feMergeNode in="blur"/>
        <feMergeNode in="SourceGraphic"/>
      </feMerge>
    </filter>';

    $gemFill = '#ffffff';
    $gemStroke = '#94a3b8';
    if ($gemColor === 'ruby') {
        $gemFill = '#e11d48';
        $gemStroke = '#881337';
    } elseif ($gemColor === 'emerald') {
        $gemFill = '#059669';
        $gemStroke = '#064e3b';
    } elseif ($gemColor === 'sapphire') {
        $gemFill = '#2563eb';
        $gemStroke = '#1e3a8a';
    }

    $illustration = '';
    if ($category === 'Ring') {
        $illustration = '
        <g transform="translate(50, 20)">
          <!-- Ring Base -->
          <ellipse cx="200" cy="220" rx="90" ry="75" fill="none" stroke="url(#gold)" stroke-width="18" filter="url(#pShadow)"/>
          <ellipse cx="200" cy="220" rx="74" ry="58" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
          <!-- Gem Crown Mount -->
          <polygon points="170,140 230,140 220,115 180,115" fill="url(#gold)"/>
          <!-- Brilliant Cut Stone -->
          <polygon points="200,65 245,105 225,140 175,140 155,105" fill="' . $gemFill . '" stroke="' . $gemStroke . '" stroke-width="2" filter="url(#gemGlow)"/>
          <polygon points="175,105 200,65 225,105 200,140" fill="rgba(255,255,255,0.4)"/>
          <!-- Sparkles -->
          <polygon points="130,85 134,94 143,97 134,100 130,109 126,100 117,97 126,94" fill="#ffffff"/>
          <polygon points="265,110 268,116 274,118 268,120 265,126 262,120 256,118 262,116" fill="#fef08a"/>
        </g>';
    } elseif ($category === 'Haram') {
        $illustration = '
        <g transform="translate(50, 10)">
          <!-- Grand Temple Arch Neckline -->
          <path d="M90,90 Q200,260 310,90" fill="none" stroke="url(#gold)" stroke-width="16" filter="url(#pShadow)"/>
          <path d="M110,105 Q200,245 290,105" fill="none" stroke="url(#gold)" stroke-width="8"/>
          <!-- Intricate temple bells/pendants -->
          <circle cx="150" cy="170" r="10" fill="#fef9c3" stroke="url(#gold)" stroke-width="2"/>
          <circle cx="175" cy="205" r="11" fill="#fef9c3" stroke="url(#gold)" stroke-width="2"/>
          <circle cx="200" cy="225" r="13" fill="#fef9c3" stroke="url(#gold)" stroke-width="2"/>
          <circle cx="225" cy="205" r="11" fill="#fef9c3" stroke="url(#gold)" stroke-width="2"/>
          <circle cx="250" cy="170" r="10" fill="#fef9c3" stroke="url(#gold)" stroke-width="2"/>
          <!-- Central Grand Locket / Medallion -->
          <path d="M165,225 L235,225 L230,285 L200,310 L170,285 Z" fill="url(#gold)" filter="url(#pShadow)"/>
          <!-- Royal Center Gem -->
          <circle cx="200" cy="265" r="22" fill="' . $gemFill . '" stroke="url(#gold)" stroke-width="4" filter="url(#gemGlow)"/>
          <circle cx="200" cy="265" r="8" fill="#ffffff" opacity="0.8"/>
          <!-- Hanging pearl cluster -->
          <circle cx="200" cy="325" r="9" fill="#fef9c3" filter="url(#pShadow)"/>
        </g>';
    } elseif ($category === 'Bracelet') {
        $illustration = '
        <g transform="translate(50, 40)">
          <!-- Luxury Link / Tennis Bracelet Silhouette -->
          <ellipse cx="200" cy="180" rx="115" ry="65" fill="none" stroke="url(#gold)" stroke-width="20" filter="url(#pShadow)"/>
          <ellipse cx="200" cy="180" rx="98" ry="48" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
          <!-- Studded Gemstones around top curve -->
          <circle cx="115" cy="190" r="8" fill="' . $gemFill . '" stroke="url(#gold)" stroke-width="2" filter="url(#gemGlow)"/>
          <circle cx="145" cy="225" r="9" fill="#ffffff" stroke="url(#gold)" stroke-width="2"/>
          <circle cx="180" cy="240" r="9" fill="' . $gemFill . '" stroke="url(#gold)" stroke-width="2" filter="url(#gemGlow)"/>
          <circle cx="220" cy="240" r="9" fill="#ffffff" stroke="url(#gold)" stroke-width="2"/>
          <circle cx="255" cy="225" r="9" fill="' . $gemFill . '" stroke="url(#gold)" stroke-width="2" filter="url(#gemGlow)"/>
          <circle cx="285" cy="190" r="8" fill="#ffffff" stroke="url(#gold)" stroke-width="2"/>
          <!-- Sparkle -->
          <polygon points="90,140 94,148 102,151 94,154 90,162 86,154 78,151 86,148" fill="#ffffff"/>
        </g>';
    } elseif ($category === 'Chain') {
        $illustration = '
        <g transform="translate(60, 20)">
          <!-- Interlocking Gold Chain Pattern -->
          <g filter="url(#pShadow)">
            <rect x="140" y="60" width="75" height="42" rx="20" fill="none" stroke="url(#gold)" stroke-width="14" transform="rotate(-28 175 80)"/>
            <rect x="160" y="110" width="75" height="42" rx="20" fill="none" stroke="url(#gold)" stroke-width="14" transform="rotate(28 195 130)"/>
            <rect x="140" y="160" width="75" height="42" rx="20" fill="none" stroke="url(#gold)" stroke-width="14" transform="rotate(-28 175 180)"/>
            <rect x="160" y="210" width="75" height="42" rx="20" fill="none" stroke="url(#gold)" stroke-width="14" transform="rotate(28 195 230)"/>
            <rect x="140" y="260" width="75" height="42" rx="20" fill="none" stroke="url(#gold)" stroke-width="14" transform="rotate(-28 175 280)"/>
          </g>
          <!-- Sparkles -->
          <polygon points="280,100 284,108 292,111 284,114 280,122 276,114 268,111 276,108" fill="#fef08a"/>
          <polygon points="100,220 103,227 110,229 103,231 100,238 97,231 90,229 97,227" fill="#ffffff"/>
        </g>';
    } elseif ($category === 'Bangles') {
        $illustration = '
        <g transform="translate(50, 30)">
          <!-- Two Ornate Bangles / Kadas -->
          <ellipse cx="170" cy="180" rx="90" ry="55" fill="none" stroke="url(#gold)" stroke-width="18" filter="url(#pShadow)"/>
          <ellipse cx="225" cy="205" rx="90" ry="55" fill="none" stroke="url(#gold)" stroke-width="18" filter="url(#pShadow)"/>
          <!-- Ruby & Emerald studded highlights -->
          <circle cx="140" cy="215" r="7" fill="' . $gemFill . '" filter="url(#gemGlow)"/>
          <circle cx="170" cy="230" r="7" fill="#ffffff"/>
          <circle cx="200" cy="225" r="7" fill="' . $gemFill . '" filter="url(#gemGlow)"/>
          <circle cx="230" cy="255" r="7" fill="#ffffff"/>
          <circle cx="260" cy="250" r="7" fill="' . $gemFill . '" filter="url(#gemGlow)"/>
          <!-- Sparkles -->
          <polygon points="110,120 114,128 122,131 114,134 110,142 106,134 98,131 106,128" fill="#ffffff"/>
        </g>';
    } elseif ($category === 'Earrings') {
        $illustration = '
        <g transform="translate(60, 20)">
          <!-- Chandelier Earrings Pair -->
          <g transform="translate(-40, 0)">
            <circle cx="170" cy="90" r="18" fill="' . $gemFill . '" stroke="url(#gold)" stroke-width="5" filter="url(#gemGlow)"/>
            <line x1="170" y1="110" x2="170" y2="140" stroke="url(#gold)" stroke-width="5"/>
            <path d="M135,170 C135,135 205,135 205,170 Z" fill="url(#gold)" filter="url(#pShadow)"/>
            <circle cx="145" cy="185" r="5" fill="#fef9c3"/>
            <circle cx="160" cy="188" r="5" fill="#fef9c3"/>
            <circle cx="175" cy="190" r="6" fill="#fef9c3"/>
            <circle cx="190" cy="188" r="5" fill="#fef9c3"/>
            <circle cx="205" cy="185" r="5" fill="#fef9c3"/>
          </g>
          <g transform="translate(50, 15)">
            <circle cx="170" cy="90" r="18" fill="' . $gemFill . '" stroke="url(#gold)" stroke-width="5" filter="url(#gemGlow)"/>
            <line x1="170" y1="110" x2="170" y2="140" stroke="url(#gold)" stroke-width="5"/>
            <path d="M135,170 C135,135 205,135 205,170 Z" fill="url(#gold)" filter="url(#pShadow)"/>
            <circle cx="145" cy="185" r="5" fill="#fef9c3"/>
            <circle cx="160" cy="188" r="5" fill="#fef9c3"/>
            <circle cx="175" cy="190" r="6" fill="#fef9c3"/>
            <circle cx="190" cy="188" r="5" fill="#fef9c3"/>
            <circle cx="205" cy="185" r="5" fill="#fef9c3"/>
          </g>
          <polygon points="80,75 84,83 92,86 84,89 80,97 76,89 68,86 76,83" fill="#ffffff"/>
        </g>';
    }

    $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $safeModel = htmlspecialchars($model, ENT_QUOTES, 'UTF-8');

    return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 420" width="500" height="420">
  <defs>
    $goldDef
  </defs>
  <!-- Background Box -->
  <rect width="500" height="420" rx="18" fill="url(#productBg)"/>
  <rect x="2" y="2" width="496" height="416" rx="16" fill="none" stroke="url(#gold)" stroke-width="1.2" stroke-opacity="0.4"/>
  
  <!-- Subtle gold corner elements -->
  <path d="M15,40 C15,22 22,15 40,15" fill="none" stroke="url(#gold)" stroke-width="1.5" opacity="0.5"/>
  <path d="M485,40 C485,22 478,15 460,15" fill="none" stroke="url(#gold)" stroke-width="1.5" opacity="0.5"/>

  <!-- Model Badge Top Right -->
  <rect x="360" y="20" width="115" height="26" rx="13" fill="rgba(20, 2, 36, 0.85)" stroke="url(#gold)" stroke-width="1"/>
  <text x="417" y="37" font-family="'Poppins', sans-serif" font-size="10" font-weight="600" fill="url(#gold)" text-anchor="middle" letter-spacing="1">
    $safeModel
  </text>

  <!-- Weight Badge Top Left -->
  <rect x="25" y="20" width="115" height="26" rx="13" fill="rgba(20, 2, 36, 0.85)" stroke="url(#gold)" stroke-width="1"/>
  <text x="82" y="37" font-family="'Poppins', sans-serif" font-size="10" font-weight="600" fill="#e0c77a" text-anchor="middle">
    ⚖ $weight g
  </text>

  $illustration

  <!-- Floating subtle glitter dots -->
  <circle cx="80" cy="300" r="1.5" fill="#fdf1a9" opacity="0.8"/>
  <circle cx="420" cy="270" r="2" fill="#ffffff" opacity="0.8"/>
  <circle cx="360" cy="340" r="1.5" fill="#fdf1a9" opacity="0.6"/>
</svg>
SVG;
}

// Generate 6 Category SVGs
$categories = [
    ['file' => 'cat_ring.svg', 'name' => 'Rings', 'sub' => 'DIAMOND & SOLITAIRES', 'icon' => 'ring'],
    ['file' => 'cat_haram.svg', 'name' => 'Haram', 'sub' => 'TEMPLE & BRIDAL HERITAGE', 'icon' => 'haram'],
    ['file' => 'cat_bracelet.svg', 'name' => 'Bracelets', 'sub' => 'ROYAL CUFFS & TENNIS', 'icon' => 'bracelet'],
    ['file' => 'cat_chain.svg', 'name' => 'Chains', 'sub' => '22K SOLID GOLD WEAVE', 'icon' => 'chain'],
    ['file' => 'cat_bangles.svg', 'name' => 'Bangles', 'sub' => 'KADA & MEENAKARI CRAFT', 'icon' => 'bangles'],
    ['file' => 'cat_earrings.svg', 'name' => 'Earrings', 'sub' => 'JHUMKAS & CHANDBALIS', 'icon' => 'earrings'],
];

foreach ($categories as $cat) {
    file_put_contents($assetsDir . '/' . $cat['file'], generateCategorySVG($cat['name'], $cat['sub'], $cat['icon']));
}

// Generate 18 Product SVGs
$products = [
    // Rings
    ['file' => 'prod_ring_1.svg', 'name' => 'Solitaire Imperial Diamond Ring', 'model' => 'RNG-001', 'cat' => 'Ring', 'gem' => 'diamond', 'style' => 1, 'wt' => 6.80],
    ['file' => 'prod_ring_2.svg', 'name' => 'Royal Crown Antique Ruby Ring', 'model' => 'RNG-002', 'cat' => 'Ring', 'gem' => 'ruby', 'style' => 2, 'wt' => 8.50],
    ['file' => 'prod_ring_3.svg', 'name' => 'Empress Floral Cocktail Ring', 'model' => 'RNG-003', 'cat' => 'Ring', 'gem' => 'emerald', 'style' => 3, 'wt' => 11.20],

    // Haram
    ['file' => 'prod_haram_1.svg', 'name' => 'Traditional Lakshmi Temple Haram', 'model' => 'HRM-101', 'cat' => 'Haram', 'gem' => 'ruby', 'style' => 1, 'wt' => 85.50],
    ['file' => 'prod_haram_2.svg', 'name' => 'Royal Peacock Polki Bridal Haram', 'model' => 'HRM-102', 'cat' => 'Haram', 'gem' => 'emerald', 'style' => 2, 'wt' => 110.00],
    ['file' => 'prod_haram_3.svg', 'name' => 'Antique Mango Mala Heritage Haram', 'model' => 'HRM-103', 'cat' => 'Haram', 'gem' => 'ruby', 'style' => 3, 'wt' => 92.40],

    // Bracelet
    ['file' => 'prod_bracelet_1.svg', 'name' => 'Byzantine Filigree Gold Bracelet', 'model' => 'BRC-201', 'cat' => 'Bracelet', 'gem' => 'diamond', 'style' => 1, 'wt' => 24.30],
    ['file' => 'prod_bracelet_2.svg', 'name' => 'Diamond Tennis Grandeur Bracelet', 'model' => 'BRC-202', 'cat' => 'Bracelet', 'gem' => 'diamond', 'style' => 2, 'wt' => 18.60],
    ['file' => 'prod_bracelet_3.svg', 'name' => 'Royal Kada Emerald Accent Bracelet', 'model' => 'BRC-203', 'cat' => 'Bracelet', 'gem' => 'emerald', 'style' => 3, 'wt' => 32.10],

    // Chain
    ['file' => 'prod_chain_1.svg', 'name' => 'Sovereign Rope Weave Gold Chain', 'model' => 'CHN-301', 'cat' => 'Chain', 'gem' => 'diamond', 'style' => 1, 'wt' => 35.00],
    ['file' => 'prod_chain_2.svg', 'name' => 'Cuban Royal Link Gold Chain', 'model' => 'CHN-302', 'cat' => 'Chain', 'gem' => 'diamond', 'style' => 2, 'wt' => 48.50],
    ['file' => 'prod_chain_3.svg', 'name' => 'Sleek Figaro Diamond-Cut Chain', 'model' => 'CHN-303', 'cat' => 'Chain', 'gem' => 'diamond', 'style' => 3, 'wt' => 22.00],

    // Bangles
    ['file' => 'prod_bangles_1.svg', 'name' => 'Antique Rajkot Meenakari Bangles', 'model' => 'BNG-401', 'cat' => 'Bangles', 'gem' => 'ruby', 'style' => 1, 'wt' => 52.80],
    ['file' => 'prod_bangles_2.svg', 'name' => 'Brilliant Cut Diamond Eternity Bangles', 'model' => 'BNG-402', 'cat' => 'Bangles', 'gem' => 'diamond', 'style' => 2, 'wt' => 42.00],
    ['file' => 'prod_bangles_3.svg', 'name' => 'South Indian Kemp Temple Bangles', 'model' => 'BNG-403', 'cat' => 'Bangles', 'gem' => 'ruby', 'style' => 3, 'wt' => 60.50],

    // Earrings
    ['file' => 'prod_earrings_1.svg', 'name' => 'Majestic Peacock Jhumkas', 'model' => 'EAR-501', 'cat' => 'Earrings', 'gem' => 'ruby', 'style' => 1, 'wt' => 28.40],
    ['file' => 'prod_earrings_2.svg', 'name' => 'Solitaire Drop Diamond Chandbalis', 'model' => 'EAR-502', 'cat' => 'Earrings', 'gem' => 'diamond', 'style' => 2, 'wt' => 21.00],
    ['file' => 'prod_earrings_3.svg', 'name' => 'Imperial Emerald Halo Studs', 'model' => 'EAR-503', 'cat' => 'Earrings', 'gem' => 'emerald', 'style' => 3, 'wt' => 9.50],
];

foreach ($products as $prod) {
    file_put_contents($assetsDir . '/' . $prod['file'], generateProductSVG($prod['name'], $prod['model'], $prod['cat'], $prod['gem'], $prod['style'], $prod['wt']));
}

echo "Successfully generated all 6 categories and 18 product SVG assets!\n";

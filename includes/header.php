<?php
/**
 * Customer Header Component
 * Royal Aura Luxury Jewellery
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';

$siteName = get_setting('site_name', 'Royal Aura Luxury Jewellery');
$logoUrl = get_setting('logo', 'assets/images/logo.svg');
$currentUser = get_current_user_data();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? $siteName) ?></title>
  <meta name="description" content="Discover royal gold and diamond jewellery masterpieces at Royal Aura. Pure 22K hallmarked gold, bridal harams, solitaire rings, and antique craftsmanship.">
  
  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?= e($logoUrl) ?>">

  <!-- Google Fonts: Playfair Display, Cormorant Garamond, Poppins, Montserrat -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Core Luxury Styling -->
  <link rel="stylesheet" href="assets/css/style.css">

  <!-- Dynamic Theme CSS Variables from Admin Database -->
  <?php render_theme_styles(); ?>
</head>
<body>

  <!-- Top Sticky Header -->
  <header class="site-header">
    <div class="container header-inner">
      <!-- Brand Logo -->
      <a href="index.php" class="logo-link" title="<?= e($siteName) ?>">
        <img src="<?= e($logoUrl) ?>" alt="<?= e($siteName) ?>" class="brand-logo-img" />
        <?php if (!str_ends_with(strtolower($logoUrl), '.svg')): ?>
          <div class="brand-text-block">
            <span class="brand-title"><?= e($siteName) ?></span>
            <span class="brand-subtitle">HAUTE JOAILLERIE</span>
          </div>
        <?php endif; ?>
      </a>

      <!-- Live Search Bar -->
      <div class="header-search-wrap">
        <div class="search-input-box">
          <span class="search-icon">🔍</span>
          <input 
            type="text" 
            id="header-live-search" 
            placeholder="Search royal rings, harams, bracelets, diamonds..." 
            autocomplete="off" 
            aria-label="Search jewellery"
          />
        </div>
        <!-- Live Search Results Dropdown -->
        <div id="search-results-dropdown" class="search-results-dropdown"></div>
      </div>

      <!-- User Nav & Logout -->
      <div class="header-actions">
        <?php if ($currentUser): ?>
          <div class="user-badge" title="Logged in customer">
            <span class="user-badge-icon">👑</span>
            <span>+91 <?= e($currentUser['phone']) ?></span>
          </div>
          <a href="logout.php" class="btn btn-sm btn-outline-gold" id="btn-header-logout">
            Logout
          </a>
        <?php else: ?>
          <a href="login.php" class="btn btn-sm btn-gold">
            Login
          </a>
        <?php endif; ?>
      </div>
    </div>
  </header>

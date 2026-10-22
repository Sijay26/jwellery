<?php
/**
 * Admin Panel Header & Navigation
 * Royal Aura Luxury Jewellery
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Strict Admin Route Guard
require_admin_login();

$siteName = get_setting('site_name', 'Royal Aura Luxury Jewellery');
$logoUrl = get_setting('logo', '../assets/images/logo.svg');
// If logo is relative path without '../'
if (!str_starts_with($logoUrl, '../') && !str_starts_with($logoUrl, 'http') && !str_starts_with($logoUrl, '/')) {
    $logoUrl = '../' . $logoUrl;
}

$admin = get_current_admin_data();
$currentScript = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($adminTitle ?? 'Admin Portal') ?> • <?= e($siteName) ?></title>
  <link rel="icon" type="image/svg+xml" href="<?= e($logoUrl) ?>">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Stylesheets -->
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <?php render_theme_styles(); ?>
</head>
<body>

<div class="admin-layout">
  <!-- Left Navigation Sidebar -->
  <aside class="admin-sidebar">
    <div class="sidebar-brand">
      <a href="index.php" style="display: flex; flex-direction: column; align-items: center; text-decoration: none;">
        <img src="<?= e($logoUrl) ?>" alt="<?= e($siteName) ?>" class="sidebar-logo" />
        <?php if (!str_ends_with(strtolower($logoUrl), '.svg')): ?>
          <div style="font-family: var(--font-serif); font-size: 1.15rem; font-weight: 700; background: var(--gradient-gold); -webkit-background-clip: text; -webkit-text-fill-color: transparent; letter-spacing: 1.5px; margin-top: 0.2rem;"><?= e($siteName) ?></div>
        <?php endif; ?>
      </a>
      <div class="sidebar-tag">ADMINISTRATION PORTAL</div>
    </div>

    <nav class="sidebar-nav">
      <a href="index.php" class="nav-item <?= $currentScript === 'index.php' ? 'active' : '' ?>">
        <span class="nav-icon">📊</span>
        <span>Dashboard</span>
      </a>
      <a href="products.php" class="nav-item <?= $currentScript === 'products.php' ? 'active' : '' ?>">
        <span class="nav-icon">💎</span>
        <span>Manage Products</span>
      </a>
      <a href="categories.php" class="nav-item <?= $currentScript === 'categories.php' ? 'active' : '' ?>">
        <span class="nav-icon">📁</span>
        <span>Categories</span>
      </a>
      <a href="orders.php" class="nav-item <?= $currentScript === 'orders.php' ? 'active' : '' ?>">
        <span class="nav-icon">🛍</span>
        <span>Customer Orders</span>
      </a>
      <a href="enquiries.php" class="nav-item <?= $currentScript === 'enquiries.php' ? 'active' : '' ?>">
        <span class="nav-icon">💬</span>
        <span>Enquiries List</span>
      </a>
      <a href="settings.php" class="nav-item <?= $currentScript === 'settings.php' ? 'active' : '' ?>">
        <span class="nav-icon">🎨</span>
        <span>Theme & Logo Settings</span>
      </a>

      <div style="margin-top: 2rem; padding: 0.8rem 1.1rem; border-top: 1px solid rgba(255,255,255,0.06);">
        <a href="../index.php" target="_blank" class="nav-item" style="color: var(--accent-light);">
          <span class="nav-icon">↗</span>
          <span>View Customer Site</span>
        </a>
      </div>
    </nav>
  </aside>

  <!-- Main Administrative Area -->
  <div class="admin-main">
    <!-- Topbar with Info & Logout -->
    <header class="admin-topbar">
      <div class="admin-title-area">
        <h2><?= e($adminTitle ?? 'Admin Dashboard') ?></h2>
        <p>Royal Aura Fine Jewellery • Management Suite</p>
      </div>

      <div class="admin-actions">
        <div class="user-badge" style="background: rgba(212,175,55,0.1); border-color: var(--accent);">
          <span class="user-badge-icon">👑</span>
          <span>Admin (+91 <?= e($admin['phone'] ?? '6369058169') ?>)</span>
        </div>
        <a href="logout.php" class="btn btn-sm btn-outline-gold" id="btn-admin-logout">
          Logout ➔
        </a>
      </div>
    </header>

    <div class="admin-body">
      <!-- Flash Alert Component -->
      <?php $flash = get_flash(); if ($flash): ?>
        <div style="background: <?= $flash['type'] === 'error' ? 'rgba(255,71,87,0.18)' : 'rgba(34,197,94,0.18)' ?>; border: 1px solid <?= $flash['type'] === 'error' ? '#ff4757' : '#22c55e' ?>; color: <?= $flash['type'] === 'error' ? '#ff6b81' : '#4ade80' ?>; padding: 1rem 1.5rem; border-radius: 14px; margin-bottom: 2rem; font-size: 0.95rem; display: flex; align-items: center; justify-content: space-between;">
          <span><?= e($flash['message']) ?></span>
          <button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; color: inherit; font-size: 1.2rem; cursor: pointer;">✕</button>
        </div>
      <?php endif; ?>

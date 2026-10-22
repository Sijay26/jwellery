-- Royal Aura Luxury Jewellery E-Commerce Database
-- Designed for MySQL / MariaDB on XAMPP / phpMyAdmin

CREATE DATABASE IF NOT EXISTS `jewellery_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `jewellery_db`;

-- 1. USERS TABLE
DROP TABLE IF EXISTS `enquiries`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `settings`;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `phone` VARCHAR(15) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. ADMINS TABLE
CREATE TABLE `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `phone` VARCHAR(15) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Admin (phone: 6369058169, password: sijay2606)
INSERT INTO `admins` (`phone`, `password_hash`) VALUES
('6369058169', '$2y$10$bsauZWFnLsHpSNFrnK8jPOacpTnBoQ6WM35p6s5vH/O4ObsNIwnCa');

-- 3. CATEGORIES TABLE
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `image`) VALUES
(1, 'Ring', 'assets/images/cat_ring.svg'),
(2, 'Haram', 'assets/images/cat_haram.svg'),
(3, 'Bracelet', 'assets/images/cat_bracelet.svg'),
(4, 'Chain', 'assets/images/cat_chain.svg'),
(5, 'Bangles', 'assets/images/cat_bangles.svg'),
(6, 'Earrings', 'assets/images/cat_earrings.svg');

-- 4. PRODUCTS TABLE
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `price_inr` DECIMAL(12,2) NOT NULL,
  `model` VARCHAR(100) NOT NULL,
  `weight_grams` DECIMAL(8,2) NOT NULL,
  `details` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `category_id`, `name`, `image`, `price_inr`, `model`, `weight_grams`, `details`) VALUES
-- RINGS (Category 1)
(1, 1, 'Solitaire Imperial Diamond Ring', 'assets/images/prod_ring_1.svg', 145000.00, 'RNG-001', 6.80, 'Crafted in 22K hallmarked yellow gold featuring a certified 1.25ct VVS1 solitaire diamond crown in a micro-pavé cathedral setting. Timeless royal elegance.'),
(2, 1, 'Royal Crown Antique Ruby Ring', 'assets/images/prod_ring_2.svg', 185000.00, 'RNG-002', 8.50, 'Exquisite 22K rose gold ring crowned with a pigeon-blood natural Burmese ruby, accented with brilliant cut round diamonds in an ornate vintage halo.'),
(3, 1, 'Empress Floral Cocktail Ring', 'assets/images/prod_ring_3.svg', 240000.00, 'RNG-003', 11.20, 'Statement cocktail ring in pure 22K gold sculpted into royal blooming petals with Colombian emerald center and polki uncut diamond accents.'),

-- HARAM (Category 2)
(4, 2, 'Traditional Lakshmi Temple Haram', 'assets/images/prod_haram_1.svg', 725000.00, 'HRM-101', 85.50, 'Masterpiece heirloom temple jewellery in 22K antique gold, hand-embossed with Goddess Lakshmi seated on a lotus flanked by divine peacocks and south sea pearl drops.'),
(5, 2, 'Royal Peacock Polki Bridal Haram', 'assets/images/prod_haram_2.svg', 980000.00, 'HRM-102', 110.00, 'Magnificent bridal necklace featuring syndicate uncut diamonds (Polki), fine Meenakari back-enameling, and natural emerald bead tassels.'),
(6, 2, 'Antique Mango Mala Heritage Haram', 'assets/images/prod_haram_3.svg', 815000.00, 'HRM-103', 92.40, 'Authentic South Indian mango (manga malai) heritage long necklace crafted in 22 karat antique nakshi finish with ruby kemp stone inlays.'),

-- BRACELET (Category 3)
(7, 3, 'Byzantine Filigree Gold Bracelet', 'assets/images/prod_bracelet_1.svg', 210000.00, 'BRC-201', 24.30, 'Solid 22K yellow gold bracelet featuring intricate interlocking Byzantine link weave with laser diamond-cut facets and double safety lock.'),
(8, 3, 'Diamond Tennis Grandeur Bracelet', 'assets/images/prod_bracelet_2.svg', 395000.00, 'BRC-202', 18.60, '18K White Gold continuous tennis line bracelet handset with 4.50 carats of premium EF color, VVS clarity round brilliant diamonds.'),
(9, 3, 'Royal Kada Emerald Accent Bracelet', 'assets/images/prod_bracelet_3.svg', 285000.00, 'BRC-203', 32.10, 'Regal openable cuff kada bracelet in 22K yellow gold with sculpted floral repoussé craftsmanship and vibrant cabochon emerald terminals.'),

-- CHAIN (Category 4)
(10, 4, 'Sovereign Rope Weave Gold Chain', 'assets/images/prod_chain_1.svg', 305000.00, 'CHN-301', 35.00, 'Heavy 22K hallmark solid gold diamond-cut French rope chain offering exceptional fluid drape, mirror luster, and lifetime durability.'),
(11, 4, 'Cuban Royal Link Gold Chain', 'assets/images/prod_chain_2.svg', 420000.00, 'CHN-302', 48.50, 'Bold 22K gold Miami Cuban curb link necklace, precision-machined with beveled diamond facets and heavy custom box tongue clasp.'),
(12, 4, 'Sleek Figaro Diamond-Cut Chain', 'assets/images/prod_chain_3.svg', 195000.00, 'CHN-303', 22.00, 'Classic 3+1 Figaro luxury gold chain in 22K yellow gold with high-polish edge beveling, perfect for daily royalty and statement pendants.'),

-- BANGLES (Category 5)
(13, 5, 'Antique Rajkot Meenakari Bangles (Pair)', 'assets/images/prod_bangles_1.svg', 460000.00, 'BNG-401', 52.80, 'Pair of heirloom kada bangles in 22K gold decorated with royal blue and red Meenakari enamel, accented with raised floral filigree carvings.'),
(14, 5, 'Brilliant Cut Diamond Eternity Bangles', 'assets/images/prod_bangles_2.svg', 650000.00, 'BNG-402', 42.00, 'Exquisite pair of 18K rose gold channel-set diamond eternity bangles containing 3.20 carats of fiery natural diamonds with concealed screw clasp.'),
(15, 5, 'South Indian Kemp Temple Bangles', 'assets/images/prod_bangles_3.svg', 530000.00, 'BNG-403', 60.50, 'Authentic Chettinad antique gold temple bangles adorned with high-grade synthetic ruby kemps and intricate god figurine carvings.'),

-- EARRINGS (Category 6)
(16, 6, 'Majestic Peacock Jhumkas', 'assets/images/prod_earrings_1.svg', 250000.00, 'EAR-501', 28.40, 'Three-tier grand bridal jhumkas in 22K gold featuring peacock stud motifs, dangling filigree bells, and hanging basra seed pearls.'),
(17, 6, 'Solitaire Drop Diamond Chandbalis', 'assets/images/prod_earrings_2.svg', 380000.00, 'EAR-502', 21.00, 'Royal crescent moon chandelier earrings in dual tone 18K gold set with brilliant cut diamonds and emerald drops for aristocratic occasions.'),
(18, 6, 'Imperial Emerald Halo Studs', 'assets/images/prod_earrings_3.svg', 165000.00, 'EAR-503', 9.50, 'Octagonal cut natural Colombian emeralds encircled by a dazzling double halo of micro-prong set diamonds in 18K white gold with screw backs.');

-- 5. ORDERS TABLE
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL,
  `product_id` INT NOT NULL,
  `customer_name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(15) NOT NULL,
  `address` TEXT NOT NULL,
  `status` ENUM('Pending', 'Confirmed', 'Delivered') DEFAULT 'Pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_orders_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. ENQUIRIES TABLE
CREATE TABLE `enquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL,
  `product_id` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_enquiries_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. SETTINGS TABLE
CREATE TABLE `settings` (
  `key` VARCHAR(100) PRIMARY KEY,
  `value` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`key`, `value`) VALUES
('theme_color', '#4b1d8f'),
('logo', 'assets/images/logo.png'),
('site_name', 'Royal Aura Luxury Jewellery'),
('whatsapp_number', '6369058169');

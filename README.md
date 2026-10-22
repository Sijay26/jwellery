# Royal Aura - Luxury Jewellery E-Commerce Platform

A production-quality, ultra-premium luxury jewellery e-commerce web application engineered for **XAMPP (Apache + PHP 8+ + MySQL/phpMyAdmin)** using pure Vanilla technologies (**PHP PDO, MySQL, HTML5, CSS3, and Vanilla JavaScript**).

---

## 🌟 Key Highlights & Design Aesthetics

- **Royal Purple & Soft Gold Haute Joaillerie Palette**:
  - Gradient Spectrum: `#1a0533` (Deep Obsidian) ➔ `#4b1d8f` (Imperial Purple) ➔ `#7b2ff7` (Radiant Amethyst) with soft gold (`#d4af37`) accents.
  - **Dynamic Theme CSS Variables**: Change the entire website's mood and color from the Admin Theme Color Picker. Matches gradients, glows, borders, and shadows automatically.
- **Glassmorphism & Micro-Interactions**:
  - Frosted glass cards (`backdrop-filter: blur(16px)`), subtle gold particle aura, smooth hover zoom on jewellery images, gold button glow animations.
  - Typography: **Playfair Display & Cormorant Garamond** for serif headings; **Poppins & Montserrat** for crisp modern sans-serif body text.
- **Indian Numbering Currency Formatting**:
  - Authentic Lakhs & Crores formatting (e.g. `₹1,45,000`, `₹7,25,000`, `₹12,50,000`).

---

## 📁 Project Folder Structure

```
d:\jwellery/ (or C:\xampp\htdocs\jewellery/)
├── admin/
│   ├── index.php             # Admin Dashboard (KPI Metrics, Recent Orders & Enquiries)
│   ├── products.php          # Add, Edit, Delete Jewellery with Image Upload
│   ├── categories.php        # Add, Rename, Change Image, Delete Categories
│   ├── orders.php            # Customer Orders List & Status Updates (Pending/Confirmed/Delivered)
│   ├── enquiries.php         # Customer Enquiries List with direct WhatsApp follow-up link
│   ├── settings.php          # Interactive Theme Color Picker, Logo Upload & Site Settings
│   ├── header.php            # Admin Navigation & Layout Component
│   ├── footer.php            # Admin Footer & Scripts
│   └── logout.php            # Admin Secure Session Invalidation
├── api/
│   ├── search.php            # Live AJAX Search with Thumbnails & Pricing
│   ├── enquiry.php           # Log customer enquiry in DB & generate WhatsApp link
│   └── buy.php               # Process Buy Now order with server validation
├── assets/
│   ├── css/
│   │   ├── style.css         # Customer Haute Joaillerie Theme & Design System
│   │   └── admin.css         # Admin Glassmorphic Dashboard & Control Center
│   ├── js/
│   │   ├── main.js           # Live search, Buy Now modal, WhatsApp enquiry handler
│   │   └── admin.js          # Color picker preview, image preview, confirmation modals
│   └── images/
│       ├── logo.svg          # Royal Aura Gold Crown Emblem & Haute Joaillerie Typography
│       ├── cat_*.svg         # 6 Luxury Category Illustrated Assets
│       └── prod_*.svg        # 18 Seeded Handcrafted Jewellery SVG Illustrations
├── config/
│   └── db.php                # PDO Connection & Settings Helpers
├── includes/
│   ├── auth.php              # Session Guards, Role Isolation, Access Control
│   ├── functions.php         # CSRF Token, INR Currency Formatter, Theme Variable Generator
│   ├── header.php            # Customer Navigation, Live Search Bar, User Indicator
│   └── footer.php            # Customer Footer & Buy Now Modal Component
├── uploads/                  # Uploaded logos and product images
├── database.sql              # Complete MySQL Database Schema + Seed Data
├── index.php                 # Customer Catalog & Home Page
├── product.php               # Product Detail Specification View
├── login.php                 # Login Page with Customer | Admin Toggle Switch
├── register.php              # Customer 10-Digit Mobile Registration & "OK" Popup
├── logout.php                # Logout Handler with No-Cache Headers
├── order_success.php         # Thank You Confirmation with 3-Second Auto-Redirect
└── README.md                 # Setup Guide & Documentation
```

---

## 🚀 Step-by-Step XAMPP Setup Instructions

### Step 1: Place Files in `htdocs`
Make sure the application folder is inside your XAMPP web root directory:
```
C:\xampp\htdocs\jewellery\
```
*(If working directly in `d:\jwellery`, you can copy all files to `C:\xampp\htdocs\jewellery` or use a directory junction `mklink /J C:\xampp\htdocs\jewellery d:\jwellery`)*.

### Step 2: Start Apache & MySQL in XAMPP Control Panel
1. Open **XAMPP Control Panel**.
2. Click **Start** next to **Apache**.
3. Click **Start** next to **MySQL**.
*(Ensure Apache is listening on port 80/443 and MySQL on port 3306)*.

### Step 3: Create Database & Import `database.sql`
You can import the database through either **phpMyAdmin** or the **Command Line**:

#### Option A: Via phpMyAdmin (Web Browser)
1. Open your browser and navigate to: `http://localhost/phpmyadmin/`
2. Click on the **Databases** tab.
3. In the "Create database" field, enter: `jewellery_db` and collation `utf8mb4_unicode_ci`, then click **Create**.
4. Select `jewellery_db` in the left sidebar.
5. Click on the **Import** tab at the top.
6. Click **Choose File** and select `database.sql` from your `jewellery` folder.
7. Scroll down and click **Import** (or **Go**).

#### Option B: Via Windows Command Prompt
```cmd
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS jewellery_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
C:\xampp\mysql\bin\mysql.exe -u root jewellery_db < C:\xampp\htdocs\jewellery\database.sql
```

### Step 4: Open the Website in Your Browser
Open your browser and navigate to:
```
http://localhost/jewellery/
```
*(This will automatically open the Login Page)*.

---

## 🔐 Credentials & Default Accounts

### 1. Admin Account (Pre-Seeded)
- **Portal Link**: `http://localhost/jewellery/login.php?role=admin` (or toggle "Admin" on `login.php`)
- **Phone**: `6369058169`
- **Password**: `sijay2606`

### 2. Customer Registration / Login
- Click **Customer** toggle on the login page or click **Register Now** (`register.php`).
- Enter any **10-digit mobile number** (e.g., `9876543210`) and a password (min 6 characters).
- Upon successful registration, a luxury **"OK • Welcome to Royalty"** popup appears, logging you in and redirecting to the catalog.

---

## 💎 Features & User Flows

### 1. Login & Registration Flow
- Seamless **Customer | Admin toggle switch** on the login page (no reload).
- Strict **back-button cache prevention** (`autocomplete="off"`, `pageshow` form reset, `Cache-Control: no-store`).
- Form submission protected against resubmission via POST -> Redirect -> GET.

### 2. Live Search with Dropdown
- Type at least 2 characters in the header search box.
- Live AJAX results return with jewellery thumbnail, title, category, weight, and formatted INR price.
- Keyboard navigation with ESC key support and outside-click dismissal.

### 3. Buy Now Flow with Strict Red Border Validation
- Clicking **Buy Now** opens the purchase concierge modal.
- If any required field (Name, 10-Digit Mobile, or Address) is empty or invalid:
  - Input box immediately highlights with a **vibrant RED border** (`2px solid #ff4757`) and red glow shadow.
  - A red validation message displays below the input.
- On valid submission, the order is stored in MySQL, and an animated **"Thank You for Your Order"** modal displays a **3-second countdown progress bar**, automatically returning the customer to the home page.

### 4. Direct WhatsApp Enquiry Desk
- Clicking **Enquiry** under any jewellery piece logs the enquiry in the `enquiries` table in MySQL.
- Instantly opens a new WhatsApp chat tab with number `6369058169` (`https://wa.me/916369058169`) containing a pre-filled luxury message:
  ```
  Hello, I am interested in this jewellery: [Name], Model: [Model], Weight: [X g], Price: ₹[Price]. Image: [full image URL]
  ```

### 5. Comprehensive Admin Suite (`/admin`)
- **Dashboard**: Live counts for total products, orders, enquiries, and registered clients, with recent order activity.
- **Product Management**: Full CRUD for jewellery with image upload (JPG, PNG, WEBP, SVG), category selector, price in INR, model code, weight in grams, and detailed descriptions.
- **Category Management**: Add, rename, replace image, and delete categories. Changes reflect immediately on the storefront.
- **Order Management**: View complete customer details, delivery addresses, items reserved, and update status in real-time (`Pending` ➔ `Confirmed` ➔ `Delivered`).
- **Customer Enquiries**: View all logged enquiries with direct "Reply on WhatsApp" links.
- **Theme Color Customizer**: Live color picker wheel + 6 curated imperial presets. Recalculates gradient stops and glows across the whole website.
- **Logo & General Settings**: Upload new brand emblem, update site title, and customize customer care WhatsApp number.

---

## 🛡️ Security Implementation

- **SQL Injection Prevention**: All queries utilize **PDO Prepared Statements** with parameterized inputs.
- **Cross-Site Scripting (XSS)**: Output escaping via `htmlspecialchars()` helper `e()`.
- **CSRF Defense**: Session-bound cryptographic tokens (`random_bytes(32)`) on forms.
- **Session Protection**: `session_regenerate_id(true)` upon authentication, `HttpOnly` cookie flags, and role segregation.
- **Secure File Uploads**: MIME type verification (`finfo_file`), extension whitelisting, file size restrictions (<= 6MB), and cryptographically random filenames.
"# jwellery" 
"# jwellery" 

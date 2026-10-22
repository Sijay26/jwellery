<?php
/**
 * API: Process Buy Now Order
 * Royal Aura Luxury Jewellery
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$customerName = trim($_POST['customer_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

$errors = [];

if ($productId <= 0) {
    $errors['product_id'] = 'Invalid jewellery product selected.';
}

if (empty($customerName) || strlen($customerName) < 2) {
    $errors['customer_name'] = 'Please enter your full name.';
}

if (!preg_match('/^[0-9]{10}$/', $phone)) {
    $errors['phone'] = 'Please enter a valid 10-digit mobile number.';
}

if (empty($address) || strlen($address) < 8) {
    $errors['address'] = 'Please provide complete delivery address with pincode.';
}

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

try {
    $pdo = get_db_connection();

    // Verify product exists
    $stmtP = $pdo->prepare("SELECT id, name, price_inr FROM products WHERE id = ?");
    $stmtP->execute([$productId]);
    $product = $stmtP->fetch();

    if (!$product) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Jewellery item not found.']);
        exit;
    }

    $userId = $_SESSION['user_id'] ?? null;

    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, product_id, customer_name, phone, address, status)
        VALUES (?, ?, ?, ?, ?, 'Pending')
    ");
    $stmt->execute([$userId, $productId, $customerName, $phone, $address]);
    $orderId = $pdo->lastInsertId();

    echo json_encode([
        'success'      => true,
        'order_id'     => $orderId,
        'product_name' => $product['name'],
        'message'      => 'Order created successfully'
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Unable to record order: ' . $e->getMessage()]);
}

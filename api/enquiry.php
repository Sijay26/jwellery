<?php
/**
 * API: Record Jewellery Enquiry & Return WhatsApp Details
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

if ($productId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid product ID']);
    exit;
}

try {
    $pdo = get_db_connection();

    // Verify product exists
    $stmtP = $pdo->prepare("SELECT id, name, model, weight_grams, price_inr, image FROM products WHERE id = ?");
    $stmtP->execute([$productId]);
    $product = $stmtP->fetch();

    if (!$product) {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
        exit;
    }

    $userId = $_SESSION['user_id'] ?? null;

    // Record enquiry in DB
    $stmt = $pdo->prepare("INSERT INTO enquiries (user_id, product_id) VALUES (?, ?)");
    $stmt->execute([$userId, $productId]);
    $enquiryId = $pdo->lastInsertId();

    $waNumber = get_setting('whatsapp_number', '6369058169');
    // Ensure WhatsApp number has 91 country code if 10 digits
    $cleanNumber = preg_replace('/[^0-9]/', '', $waNumber);
    if (strlen($cleanNumber) === 10) {
        $cleanNumber = '91' . $cleanNumber;
    }

    echo json_encode([
        'success'         => true,
        'enquiry_id'      => $enquiryId,
        'whatsapp_number' => $cleanNumber,
        'product'         => [
            'name'         => $product['name'],
            'model'        => $product['model'],
            'weight_grams' => $product['weight_grams'],
            'price_inr'    => format_inr($product['price_inr']),
            'image'        => $product['image'],
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database operation failed: ' . $e->getMessage()]);
}

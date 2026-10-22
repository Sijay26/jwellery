<?php
/**
 * API: Live Search Products
 * Royal Aura Luxury Jewellery
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$q = trim($_GET['q'] ?? '');

if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

try {
    $pdo = get_db_connection();
    $searchTerm = '%' . $q . '%';
    $stmt = $pdo->prepare("
        SELECT p.id, p.name, p.image, p.price_inr, p.model, p.weight_grams, c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.name LIKE ? OR p.model LIKE ? OR c.name LIKE ?
        ORDER BY p.name ASC
        LIMIT 8
    ");
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    $results = $stmt->fetchAll();

    $output = [];
    foreach ($results as $row) {
        $output[] = [
            'id'              => (int)$row['id'],
            'name'            => $row['name'],
            'image'           => $row['image'],
            'price_inr'       => (float)$row['price_inr'],
            'formatted_price' => format_inr($row['price_inr']),
            'model'           => $row['model'],
            'weight_grams'    => (float)$row['weight_grams'],
            'category_name'   => $row['category_name'] ?? 'Jewellery',
        ];
    }

    echo json_encode($output);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed']);
}

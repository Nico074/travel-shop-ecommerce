<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success'  => false,
        'redirect' => '../login.php',
        'message'  => 'You must be logged in to add items to the cart.'
    ]);
    exit;
}

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$id  = isset($_POST['id'])  ? (int) $_POST['id']  : 0;
$qty = isset($_POST['qty']) ? (int) $_POST['qty'] : 0;

// Basic validation
if ($id <= 0 || $qty <= 0 || $qty > 999) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// Fetch product
$stmt = $conn->prepare("SELECT id, name, price, image, stock FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result  = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

if ($product['stock'] < $qty) {
    echo json_encode(['success' => false, 'message' => 'Not enough stock']);
    exit;
}

// Update stock
$newStock = $product['stock'] - $qty;
$update = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
$update->bind_param("ii", $newStock, $id);
$update->execute();

// Add to cart (session)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += $qty;
} else {
    $_SESSION['cart'][$id] = [
        'id'    => (int) $product['id'],
        'name'  => $product['name'],
        'price' => (float) $product['price'],
        'image' => $product['image'],
        'qty'   => $qty,
    ];
}
$cartCount = array_sum(array_column($_SESSION['cart'], 'qty'));

echo json_encode([
    'success'   => true,
    'newStock'  => $newStock,
    'cartCount' => $cartCount,
    'message'   => 'Added to cart'
]);

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

include '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$id  = isset($_POST['id'])  ? (int) $_POST['id']  : 0;
$qty = isset($_POST['qty']) ? (int) $_POST['qty'] : 0;

if ($id <= 0 || $qty <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// Récupérer le produit
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
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

// Mettre à jour le stock
$newStock = $product['stock'] - $qty;

$update = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
$update->bind_param("ii", $newStock, $id);
$update->execute();

// Ajouter au panier (session)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += $qty;
} else {
    $_SESSION['cart'][$id] = [
        'id'    => $product['id'],
        'name'  => $product['name'],
        'price' => $product['price'],
        'image' => $product['image'], // ex: images/products/winter-jacket.jpg
        'qty'   => $qty,
    ];
}

echo json_encode([
    'success'  => true,
    'newStock' => $newStock,
    'message'  => 'Added to cart'
]);

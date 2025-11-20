<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

$order_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$userId   = (int) $_SESSION['user_id'];

if ($order_id <= 0) {
    exit("Commande introuvable.");
}

// Récupère la commande de l'utilisateur
$orderStmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$orderStmt->bind_param("ii", $order_id, $userId);
$orderStmt->execute();
$orderRes = $orderStmt->get_result();
$order = $orderRes->fetch_assoc();

if (!$order) {
    exit("Accès refusé.");
}

// Récupère les items
$itemStmt = $conn->prepare("SELECT product_name, quantity, price FROM order_items WHERE order_id = ?");
$itemStmt->bind_param("i", $order_id);
$itemStmt->execute();
$items = $itemStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .invoice{width:90%;max-width:900px;margin:3rem auto;background:white;padding:3rem;border-radius:15px;box-shadow:0 20px 40px rgba(0,0,0,0.1);}
        .invoice h2{margin-bottom:1rem;}
        .invoice-item{display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #eee;}
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="invoice">
    <h2>Order #<?php echo $order['id']; ?></h2>
    <p><strong>Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($order['full_name']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?>, <?php echo htmlspecialchars($order['city']); ?> <?php echo htmlspecialchars($order['postcode']); ?></p>

    <h3>Items:</h3>
    <?php while ($item = $items->fetch_assoc()): ?>
        <div class="invoice-item">
            <span><?php echo htmlspecialchars($item['product_name']); ?> - <?php echo (int) $item['quantity']; ?></span>
            <span>$<?php echo number_format($item['price'] * $item['quantity'],2); ?></span>
        </div>
    <?php endwhile; ?>

    <h2>Total: $<?php echo number_format($order['total'],2); ?></h2>
</div>

</body>
</html>


<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';

$userId = $_SESSION['user_id'];

// ----- Gestion annulation de commande -----
if (isset($_GET['cancel'])) {
    $cancelId = (int) $_GET['cancel'];

    // on vérifie que la commande appartient bien à l'utilisateur
    $check = $conn->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ?");
    $check->bind_param("ii", $cancelId, $userId);
    $check->execute();
    $checkRes = $check->get_result();

    if ($checkRes->num_rows > 0) {
        // supprimer d'abord les items
        $conn->query("DELETE FROM order_items WHERE order_id = $cancelId");
        // puis la commande
        $conn->query("DELETE FROM orders WHERE id = $cancelId");
    }

    header("Location: cart.php?cancelled=1");
    exit;
}

// ----- PANIER -----
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cartTotal = 0;
foreach ($cartItems as $item) {
    $cartTotal += $item['price'] * $item['qty'];
}

// ----- COMMANDES -----
$ordersQuery = $conn->prepare("
    SELECT * FROM orders 
    WHERE user_id = ? 
    ORDER BY order_date DESC
");
$ordersQuery->bind_param("i", $userId);
$ordersQuery->execute();
$ordersResult = $ordersQuery->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart & Orders — Travel Shop</title>
  <link rel="stylesheet" href="css/styles.css">

  <style>
    .cart-page {
        width: 95%;
        max-width: 1000px;
        margin: 2rem auto;
        background: #fff;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .cart-items {
        border-bottom: 1px solid #eee;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .cart-item-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: .75rem 0;
        border-bottom: 1px solid #f5f5f5;
    }

    .cart-item-row:last-child {
        border-bottom: none;
    }

    .cart-item-row img {
        width: 70px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .cart-summary-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
    }

    .btn-primary, .btn-secondary, .btn-danger {
        padding: 0.6rem 1.3rem;
        border-radius: 8px;
        border: none;
        text-decoration: none;
        font-size: 15px;
        cursor: pointer;
    }

    .btn-primary {
        background: #ff7a00;
        color: white;
    }

    .btn-secondary {
        background: #f1f1f1;
        color: #333;
    }

    .btn-danger {
        background: #ff3b30;
        color: white;
    }

    .orders-section h2 {
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }

    .order-card {
        border-top: 1px solid #eee;
        padding: 1rem 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-actions a {
        margin-left: 0.5rem;
    }
  </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="cart-page">

    <h1>Your Cart</h1>

    <div class="cart-items">
        <?php if (empty($cartItems)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item-row">
                    <img src="<?php echo $item['image']; ?>" alt="">
                    <div style="flex:1;">
                        <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                        Qty: <?php echo $item['qty']; ?>
                    </div>
                    <div>
                        $<?php echo number_format($item['price'] * $item['qty'], 2); ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="cart-summary-actions">
                <div><strong>Total: $<?php echo number_format($cartTotal, 2); ?></strong></div>
                <div>
                    <a href="index.php" class="btn-secondary">Continue Shopping</a>
                    <a href="checkout.php" class="btn-primary">Proceed to Checkout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="orders-section">
        <h2>My Orders</h2>

        <?php if ($ordersResult->num_rows === 0): ?>
            <p>You have no orders yet.</p>
        <?php else: ?>
            <?php if (isset($_GET['cancelled'])): ?>
                <p style="color:#ff3b30;">Order cancelled successfully.</p>
            <?php endif; ?>

            <?php while ($order = $ordersResult->fetch_assoc()): ?>
                <div class="order-card">
                    <div>
                        <strong>Order #<?php echo $order['id']; ?></strong><br>
                        Total: $<?php echo number_format($order['total'],2); ?><br>
                        Date: <?php echo $order['order_date']; ?>
                    </div>
                    <div class="order-actions">
                        <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn-primary">View</a>
                        <a href="cart.php?cancel=<?php echo $order['id']; ?>" 
                           class="btn-danger"
                           onclick="return confirm('Cancel this order?');">
                           Cancel
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>


<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';

$userId = $_SESSION['user_id'];

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
  <title>My Orders — Travel Shop</title>
  <link rel="stylesheet" href="css/styles.css">

  <style>
    .orders-container {
        width: 95%;
        max-width: 1000px;
        margin: 2rem auto;
        background: #fff;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .order-card {
        border-bottom: 1px solid #eee;
        padding: 1.5rem 0;
    }

    .order-card:last-child {
        border-bottom: none;
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .order-items {
        margin-top: 0.5rem;
        padding-left: 1rem;
    }

    .order-items li {
        margin-bottom: 0.25rem;
    }
  </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="orders-container">
    <h1>My Orders</h1>

    <?php if ($ordersResult->num_rows === 0): ?>
        <p>You have no orders yet.</p>
    <?php else: ?>
        <?php while ($order = $ordersResult->fetch_assoc()): ?>
            <?php
            $orderId = $order['id'];
            $itemsResult = $conn->query("
                SELECT * FROM order_items 
                WHERE order_id = $orderId
            ");
            ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <strong>Order #<?php echo $order['id']; ?></strong><br>
                        Date: <?php echo $order['order_date']; ?>
                    </div>
                    <div>
                        <strong>Total: $<?php echo number_format($order['total'], 2); ?></strong>
                    </div>
                </div>

                <ul class="order-items">
                    <?php while ($item = $itemsResult->fetch_assoc()): ?>
                        <li>
                            <?php echo htmlspecialchars($item['product_name']); ?>
                            × <?php echo $item['quantity']; ?> — 
                            $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>

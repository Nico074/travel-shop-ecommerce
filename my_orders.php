<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

$userId = (int) $_SESSION['user_id'];

// Fetch user orders
$orderStmt = $conn->prepare("
    SELECT id, total, order_date
    FROM orders
    WHERE user_id = ?
    ORDER BY order_date DESC
");
$orderStmt->bind_param("i", $userId);
$orderStmt->execute();
$orderRes = $orderStmt->get_result();

$orders = [];
$orderIds = [];
while ($row = $orderRes->fetch_assoc()) {
    $orders[] = $row;
    $orderIds[] = (int) $row['id'];
}

// Fetch items in one query when orders exist
$itemsByOrder = [];
if (!empty($orderIds)) {
    $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
    $types = str_repeat('i', count($orderIds));

    $itemStmt = $conn->prepare("
        SELECT order_id, product_name, quantity, price
        FROM order_items
        WHERE order_id IN ($placeholders)
    ");
    $itemStmt->bind_param($types, ...$orderIds);
    $itemStmt->execute();
    $itemsRes = $itemStmt->get_result();

    while ($item = $itemsRes->fetch_assoc()) {
        $oid = (int) $item['order_id'];
        if (!isset($itemsByOrder[$oid])) {
            $itemsByOrder[$oid] = [];
        }
        $itemsByOrder[$oid][] = $item;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders - Travel Shop</title>
  <link rel="stylesheet" href="css/styles.css">
  <style>
    .orders-container{width:95%;max-width:1000px;margin:2rem auto;background:#fff;padding:2rem;border-radius:15px;box-shadow:0 10px 25px rgba(0,0,0,0.1);}
    .order-card{border-bottom:1px solid #eee;padding:1.5rem 0;}
    .order-card:last-child{border-bottom:none;}
    .order-header{display:flex;justify-content:space-between;margin-bottom:0.5rem;}
    .order-items{margin-top:0.5rem;padding-left:1rem;}
    .order-items li{margin-bottom:0.25rem;}
  </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="orders-container">
    <h1>My Orders</h1>

    <?php if (empty($orders)): ?>
        <p>You have no orders yet.</p>
    <?php else: ?>
        <?php foreach ($orders as $order):
            $oid = (int) $order['id'];
            $items = $itemsByOrder[$oid] ?? [];
        ?>
            <div class="order-card">
                <div class="order-header">
                    <div>
                        <strong>Order #<?php echo $oid; ?></strong><br>
                        Date: <?php echo htmlspecialchars($order['order_date']); ?>
                    </div>
                    <div>
                        <strong>Total: $<?php echo number_format($order['total'], 2); ?></strong>
                    </div>
                </div>

                <?php if (!empty($items)): ?>
                    <ul class="order-items">
                        <?php foreach ($items as $item): ?>
                            <li>
                                <?php echo htmlspecialchars($item['product_name']); ?>
                                - <?php echo (int) $item['quantity']; ?> x
                                $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>

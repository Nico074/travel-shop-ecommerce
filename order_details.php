<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';

$order_id = $_GET['id'];

// Vérifie que la commande appartient au user
$uid = $_SESSION['user_id'];
$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orders WHERE id=$order_id AND user_id=$uid"));

if (!$order) {
    die("Access denied.");
}

$items = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id=$order_id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .invoice {
            width: 90%;
            max-width: 900px;
            margin: 3rem auto;
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .invoice h2 { margin-bottom: 1rem; }
        .invoice-item {
            display: flex;
            justify-content: space-between;
            padding: .5rem 0;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="invoice">
    <h2>Order #<?php echo $order['id']; ?></h2>
    <p><strong>Date:</strong> <?php echo $order['order_date']; ?></p>
    <p><strong>Name:</strong> <?php echo $order['full_name']; ?></p>
    <p><strong>Address:</strong> <?php echo $order['address']; ?>, <?php echo $order['city']; ?> <?php echo $order['postcode']; ?></p>

    <h3>Items:</h3>
    <?php while ($item = mysqli_fetch_assoc($items)): ?>
        <div class="invoice-item">
            <span><?php echo $item['product_name']; ?> × <?php echo $item['quantity']; ?></span>
            <span>$<?php echo number_format($item['price'] * $item['quantity'],2); ?></span>
        </div>
    <?php endwhile; ?>

    <h2>Total: $<?php echo number_format($order['total'],2); ?></h2>
</div>

</body>
</html>

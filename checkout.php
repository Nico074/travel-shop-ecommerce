<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

$userId    = (int) $_SESSION['user_id'];
$cartItems = $_SESSION['cart'] ?? [];
$paymentSuccess = false;
$orderId = null;

// If cart is empty, redirect to cart
if (empty($cartItems)) {
    header("Location: cart.php");
    exit;
}

// Recalculate total from the database (price/stock safety)
$productIds = array_keys($cartItems);
$placeholders = implode(',', array_fill(0, count($productIds), '?'));
$types = str_repeat('i', count($productIds));

$products = [];
$stmt = $conn->prepare("SELECT id, name, price, stock, image FROM products WHERE id IN ($placeholders)");
$stmt->bind_param($types, ...$productIds);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $products[$row['id']] = $row;
}

// Recalculated total
$orderTotal = 0;
foreach ($cartItems as $item) {
    $pid = $item['id'];
    if (!isset($products[$pid])) {
        continue; // product not found
    }
    $qty = max(1, (int) $item['qty']);
    $orderTotal += $products[$pid]['price'] * $qty;
}

// Handle form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $address   = trim($_POST['address']);
    $city      = trim($_POST['city']);
    $postcode  = trim($_POST['postcode']);

    try {
        $conn->begin_transaction();

        $orderStmt = $conn->prepare("
            INSERT INTO orders (user_id, full_name, email, phone, address, city, postcode, total)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $orderStmt->bind_param(
            "issssssd",
            $userId,
            $full_name,
            $email,
            $phone,
            $address,
            $city,
            $postcode,
            $orderTotal
        );
        $orderStmt->execute();
        $orderId = $conn->insert_id;

        $itemStmt = $conn->prepare("
            INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($cartItems as $item) {
            $pid = $item['id'];
            if (!isset($products[$pid])) {
                continue;
            }
            $qty   = max(1, (int) $item['qty']);
            $pname = $products[$pid]['name'];
            $price = $products[$pid]['price'];

            $itemStmt->bind_param("iisid", $orderId, $pid, $pname, $qty, $price);
            $itemStmt->execute();
        }

        $conn->commit();
        $_SESSION['cart'] = [];
        $paymentSuccess = true;
    } catch (Exception $e) {
        $conn->rollback();
        $errorMsg = "Payment error, please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout - Travel Shop</title>
  <link rel="stylesheet" href="css/styles.css">
  <style>
    .checkout-container{width:95%;max-width:1200px;margin:2rem auto;display:flex;gap:2rem;}
    .order-summary,.checkout-form{flex:1;background:white;padding:2rem;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,0.1);}
    .summary-item{display:flex;gap:1rem;border-bottom:1px solid #ececec;padding-bottom:1rem;margin-bottom:1rem;}
    .summary-item img{width:90px;border-radius:10px;border:1px solid #ccc;}
    .total-price{font-size:22px;text-align:right;margin-top:1rem;font-weight:bold;}
    .checkout-form input{width:100%;padding:12px;border-radius:8px;border:1px solid #ccc;}
    .pay-btn{margin-top:1rem;width:100%;padding:1rem;font-size:20px;background:#ff7a00;color:white;border:none;border-radius:10px;cursor:pointer;}
    .pay-btn:hover{background:#ff6500;}
    .success-box{width:90%;max-width:700px;margin:4rem auto;padding:3rem;background:white;border-radius:20px;text-align:center;box-shadow:0 20px 40px rgba(0,0,0,0.1);}
    .error-message{color:#c00000;text-align:center;margin:1rem 0;}
  </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<?php if ($paymentSuccess): ?>
  <div class="success-box">
      <h1>Payment confirmed!</h1>
      <p>Your order #<?php echo (int) $orderId; ?> is confirmed.</p>
      <a href="my_orders.php" class="pay-btn" style="width:auto;">View my orders</a>
  </div>
<?php else: ?>
  <?php if (!empty($errorMsg)): ?>
    <div class="error-message"><?php echo htmlspecialchars($errorMsg); ?></div>
  <?php endif; ?>

  <div class="checkout-container">
    <div class="order-summary">
        <h2>Order Summary</h2>
        <?php foreach ($cartItems as $item):
            $pid = $item['id'];
            if (!isset($products[$pid])) continue;
            $p = $products[$pid];
            $qty = max(1, (int) $item['qty']);
        ?>
            <div class="summary-item">
                <img src="<?php echo htmlspecialchars($p['image']); ?>" alt="">
                <div>
                    <strong><?php echo htmlspecialchars($p['name']); ?></strong><br>
                    Qty: <?php echo $qty; ?><br>
                    Price: $<?php echo number_format($p['price'], 2); ?>
                </div>
                <div>
                    <strong>$<?php echo number_format($p['price'] * $qty, 2); ?></strong>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="total-price">Total: $<?php echo number_format($orderTotal, 2); ?></div>
    </div>

    <div class="checkout-form">
        <h2>Billing & Shipping Information</h2>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="city" placeholder="City" required>
            <input type="text" name="postcode" placeholder="Postcode" required>

            <h3 style="margin-top:1rem;">Payment Details (Example Only)</h3>
            <input type="text" name="card_name" placeholder="Name on Card" required>
            <input type="text" name="card_number" placeholder="Card Number" required>

            <div style="display:flex; gap:1rem;">
                <input type="text" name="expiry" placeholder="MM/YY" required>
                <input type="text" name="cvv" placeholder="CVV" required>
            </div>

            <button class="pay-btn" type="submit">
                Pay $<?php echo number_format($orderTotal, 2); ?>
            </button>
        </form>
    </div>
  </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
</body>
</html>

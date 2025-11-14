<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config.php';

// Vérification utilisateur connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Récupération panier
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

foreach ($cartItems as $item) {
    $total += $item['price'] * $item['qty'];
}

$paymentSuccess = false;
$orderId = null;

// 🔥 TRAITEMENT DU FORMULAIRE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sécurisation des champs
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']);
    $address   = mysqli_real_escape_string($conn, $_POST['address']);
    $city      = mysqli_real_escape_string($conn, $_POST['city']);
    $postcode  = mysqli_real_escape_string($conn, $_POST['postcode']);

    // 1️⃣ INSÈRE LA COMMANDE DANS orders
    $sql = "INSERT INTO orders (user_id, full_name, email, phone, address, city, postcode, total)
            VALUES ('{$_SESSION['user_id']}', '$full_name', '$email', '$phone', '$address', '$city', '$postcode', '$total')";

    if (mysqli_query($conn, $sql)) {

        $orderId = mysqli_insert_id($conn); // ID de la commande enregistrée

        // 2️⃣ INSÈRE CHAQUE ARTICLE DANS order_items
        foreach ($cartItems as $item) {
            $product_id   = $item['id'];
            $product_name = mysqli_real_escape_string($conn, $item['name']);
            $price        = $item['price'];
            $qty          = $item['qty'];

            $sqlItem = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
                        VALUES ('$orderId', '$product_id', '$product_name', '$qty', '$price')";
            mysqli_query($conn, $sqlItem);
        }

        // 3️⃣ ON VIDE LE PANIER
        $_SESSION['cart'] = [];
        $paymentSuccess = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout — Travel Shop</title>
  <!-- IMPORTANT : pas de ../ car checkout.php est à la racine -->
  <link rel="stylesheet" href="css/styles.css">

<style>
.checkout-container {
    width: 95%;
    max-width: 1200px;
    margin: 2rem auto;
    display: flex;
    gap: 2rem;
}

.order-summary, .checkout-form {
    flex: 1;
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.summary-item {
    display: flex;
    gap: 1rem;
    border-bottom: 1px solid #ececec;
    padding-bottom: 1rem;
    margin-bottom: 1rem;
}

.summary-item img {
    width: 90px;
    border-radius: 10px;
    border: 1px solid #ccc;
}

.total-price {
    font-size: 22px;
    text-align: right;
    margin-top: 1rem;
    font-weight: bold;
}

.checkout-form input {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

.pay-btn {
    margin-top: 1rem;
    width: 100%;
    padding: 1rem;
    font-size: 20px;
    background: #ff7a00;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
}
.pay-btn:hover {
    background: #ff6500;
}

.success-box {
    width: 90%;
    max-width: 700px;
    margin: 4rem auto;
    padding: 3rem;
    background: white;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}
</style>

</head>
<body>

<?php include 'includes/header.php'; ?>

<?php if ($paymentSuccess): ?>

<div class="success-box">
    <h1>🎉 Payment Successful!</h1>
    <p>Your order #<?php echo $orderId; ?> has been confirmed.</p>

    <a href="my_orders.php" class="pay-btn" style="width:auto;">
        View My Orders
    </a>
</div>

<?php else: ?>

<div class="checkout-container">

    <!-- RÉSUMÉ -->
    <div class="order-summary">
        <h2>Order Summary</h2>

        <?php foreach ($cartItems as $item): ?>
            <div class="summary-item">
                <img src="<?php echo $item['image']; ?>" alt="">
                <div>
                    <strong><?php echo $item['name']; ?></strong><br>
                    Qty: <?php echo $item['qty']; ?><br>
                    Price: $<?php echo number_format($item['price'], 2); ?>
                </div>
                <div>
                    <strong>$<?php echo number_format($item['price'] * $item['qty'], 2); ?></strong>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="total-price">Total: $<?php echo number_format($total, 2); ?></div>
    </div>

    <!-- FORMULAIRE -->
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
                Pay $<?php echo number_format($total, 2); ?>
            </button>

        </form>
    </div>

</div>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>

</body>
</html>


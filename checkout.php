<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['qty'];
}

$paymentSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ici on "simule" juste la validation, pas de vrai paiement
    // Tu pourrais vérifier que tous les champs sont remplis, etc.
    $paymentSuccess = true;

    // On vide le panier après paiement
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout — Travel Shop</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="product-page">
  <section class="product-showcase" style="width:100%; max-width:1100px; margin: 2rem auto;">

    <?php if ($paymentSuccess): ?>

      <h1>Payment Successful 🎉</h1>
      <p>Thank you for your purchase! Your order has been confirmed.</p>
      <a href="index.php" class="add-to-cart" style="text-decoration:none; margin-top:1.5rem; display:inline-block;">
        Back to Home
      </a>

    <?php else: ?>

      <h1>Checkout</h1>

      <?php if (empty($cartItems)) : ?>
        <p>Your cart is empty.</p>
        <a href="index.php" class="add-to-cart" style="text-decoration:none; margin-top:1.5rem; display:inline-block;">
          Go Shopping
        </a>
      <?php else : ?>

        <!-- Résumé du panier -->
        <h2>Order Summary</h2>
        <ul>
          <?php foreach ($cartItems as $item): ?>
            <li>
              <?php echo htmlspecialchars($item['name']); ?> 
              × <?php echo $item['qty']; ?> — 
              $<?php echo number_format($item['price'] * $item['qty'], 2); ?>
            </li>
          <?php endforeach; ?>
        </ul>
        <p><strong>Total: $<?php echo number_format($total, 2); ?></strong></p>

        <!-- Formulaire d'information + "paiement" -->
        <h2>Billing & Shipping Information</h2>

        <form method="post" style="margin-top:1rem; display:grid; gap:1rem; max-width:500px;">

          <!-- Infos client -->
          <div>
            <label for="full_name">Full Name</label><br>
            <input type="text" id="full_name" name="full_name" required>
          </div>

          <div>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required>
          </div>

          <div>
            <label for="phone">Phone Number</label><br>
            <input type="text" id="phone" name="phone" required>
          </div>

          <div>
            <label for="address">Address</label><br>
            <input type="text" id="address" name="address" required>
          </div>

          <div>
            <label for="city">City</label><br>
            <input type="text" id="city" name="city" required>
          </div>

          <div>
            <label for="postcode">Postcode</label><br>
            <input type="text" id="postcode" name="postcode" required>
          </div>

          <!-- Infos carte (FAKE) -->
          <h3>Payment Details (Example Only)</h3>

          <div>
            <label for="card_name">Name on Card</label><br>
            <input type="text" id="card_name" name="card_name" required>
          </div>

          <div>
            <label for="card_number">Card Number</label><br>
            <input type="text" id="card_number" name="card_number" required>
          </div>

          <div style="display:flex; gap:1rem;">
            <div>
              <label for="card_expiry">Expiry (MM/YY)</label><br>
              <input type="text" id="card_expiry" name="card_expiry" required>
            </div>
            <div>
              <label for="card_cvv">CVV</label><br>
              <input type="text" id="card_cvv" name="card_cvv" required>
            </div>
          </div>

          <button type="submit" class="add-to-cart">
            Pay $<?php echo number_format($total, 2); ?>
          </button>
        </form>

      <?php endif; ?>

    <?php endif; ?>

  </section>
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>

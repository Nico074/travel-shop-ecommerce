<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Calcul du total
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['qty'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart — Travel Shop</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="product-page">
  <section class="product-showcase" style="width:100%; max-width:1100px; margin: 2rem auto;">
    <h1>Your Cart</h1>

    <?php if (empty($cartItems)) : ?>
      <p>Your cart is empty.</p>
    <?php else : ?>
      <table class="cart-table" style="width:100%; border-collapse:collapse; margin-top:1rem;">
        <thead>
          <tr>
            <th style="text-align:left; padding:8px;">Product</th>
            <th style="text-align:left; padding:8px;">Image</th>
            <th style="text-align:center; padding:8px;">Qty</th>
            <th style="text-align:right; padding:8px;">Price</th>
            <th style="text-align:right; padding:8px;">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cartItems as $item) : 
              $subtotal = $item['price'] * $item['qty'];
          ?>
            <tr>
              <td style="padding:8px;"><?php echo htmlspecialchars($item['name']); ?></td>
              <td style="padding:8px;">
                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt=""
                     style="width:70px; border-radius:8px;">
              </td>
              <td style="padding:8px; text-align:center;"><?php echo $item['qty']; ?></td>
              <td style="padding:8px; text-align:right;">$<?php echo number_format($item['price'], 2); ?></td>
              <td style="padding:8px; text-align:right;">$<?php echo number_format($subtotal, 2); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div style="margin-top:1.5rem; text-align:right;">
        <h2>Total: $<?php echo number_format($total, 2); ?></h2>
      </div>

      <div style="margin-top:1.5rem; display:flex; justify-content:space-between;">
        <a href="index.php" class="add-to-cart" style="text-decoration:none; padding:0.75rem 1.5rem;">
          Continue Shopping
        </a>

        <a href="checkout.php" class="add-to-cart" style="text-decoration:none; padding:0.75rem 1.5rem;">
          Proceed to Checkout
        </a>
      </div>
    <?php endif; ?>
  </section>
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>

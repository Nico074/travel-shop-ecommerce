<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel Shop - Brisbane</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>

  <?php include 'includes/header.php'; ?>

  <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
      <div class="success-message logout-message">You have been logged out successfully.</div>
  <?php endif; ?>

  <section class="hero">
      <img id="hero-image" src="images/travel2.jpg" alt="Brisbane City" class="hero-img">
      <div class="hero-overlay">
          <h1>Travel Shop</h1>
          <p>Everything you need to go on an adventure</p>
      </div>
  </section>

  <section class="categories">
      <div class="category-card" style="background-image:url('images/backpack.jpg')">
          <div class="overlay">
              <h2>Backpacks</h2>
              <a href="products/backpacks.php" class="btn">Shop Backpacks</a>
          </div>
      </div>

      <div class="category-card" style="background-image:url('images/tent.jpg')">
          <div class="overlay">
              <h2>Tents</h2>
              <a href="products/tents.php" class="btn">Shop Tents</a>
          </div>
      </div>

      <div class="category-card" style="background-image:url('images/jacket.jpg')">
          <div class="overlay">
              <h2>Jackets</h2>
              <a href="products/jackets.php" class="btn">Shop Jackets</a>
          </div>
      </div>
  </section>

  <script src="js/app.js"></script>
  <?php include 'includes/footer.php'; ?>
</body>
</html>

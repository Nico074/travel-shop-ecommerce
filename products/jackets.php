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
  <title>Jackets — Travel Shop</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <?php include '../includes/header.php'; ?>

  <main>
    <h2>Our Jackets</h2>
    <div id="products-grid"></div>
  </main>

  <script src="../js/app.js"></script>
  <script>
    renderProducts(products.filter(p => p.category === "jacket"));
  </script>
  <?php include '../includes/footer.php'; ?>
</body>
</html>

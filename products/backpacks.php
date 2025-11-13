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
  <title>Backpacks — Travel Shop</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <?php include '../includes/header.php'; ?>

<main class="product-page">
  <?php
  include '../config.php';
  $query = "SELECT * FROM products WHERE category='backpack'";
  $result = mysqli_query($conn, $query);

  while ($row = mysqli_fetch_assoc($result)) {
  ?>
    <section class="product-showcase">
      <div class="product-image">
        <img src="../<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
      </div>

      <div class="product-details">
        <h1><?php echo $row['name']; ?></h1>
        <p class="product-description">
          A stylish and durable backpack — perfect for travel, hiking, or everyday use.
        </p>

        <div class="product-price">
          <span class="current-price">$<?php echo number_format($row['price'], 2); ?></span>
        </div>

        <div class="product-stock">
          <strong>Stock:</strong>
          <span id="stock-<?php echo $row['id']; ?>"><?php echo $row['stock']; ?></span> available
        </div>

        <div class="product-options">
          <label for="size-<?php echo $row['id']; ?>">Select Size:</label>
          <select id="size-<?php echo $row['id']; ?>">
            <option value="S">Small</option>
            <option value="M">Medium</option>
            <option value="L">Large</option>
          </select>
        </div>

        <div class="product-quantity">
          <label>Quantity:</label>
          <input type="number" id="qty-<?php echo $row['id']; ?>" value="1" min="1" max="<?php echo $row['stock']; ?>">
        </div>

        <button class="add-to-cart" onclick="addToCart(<?php echo $row['id']; ?>)">Add to Cart</button>
      </div>
    </section>
  <?php } ?>
</main>

<?php include '../includes/footer.php'; ?>

<script>
function addToCart(productId) {
  const qty = document.getElementById(`qty-${productId}`).value;

  fetch('../includes/update_stock.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${productId}&qty=${qty}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      document.getElementById(`stock-${productId}`).textContent = data.newStock;
      alert('✅ Product added to cart and stock updated!');
    } else {
      alert('❌ Not enough stock!');
    }
  });
}
</script>

</body>
</html>




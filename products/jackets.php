<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config.php';

// Récupère les vestes
$stmt = $conn->prepare("SELECT id, name, price, stock, image FROM products WHERE category = 'jacket'");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jackets - Travel Shop</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

<?php include '../includes/header.php'; ?>

<main class="product-page">
  <?php while ($row = $result->fetch_assoc()): ?>
    <section class="product-showcase">
      <div class="product-image">
        <img src="../<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
      </div>

      <div class="product-details">
        <h1><?php echo htmlspecialchars($row['name']); ?></h1>

        <p class="product-description">
          A warm, durable and weather-resistant jacket — ideal for travel and outdoor adventures.
        </p>

        <div class="product-price">
          <span class="current-price">$<?php echo number_format((float)$row['price'], 2); ?></span>
        </div>

        <div class="product-stock">
          <strong>Stock:</strong>
          <span id="stock-<?php echo (int)$row['id']; ?>"><?php echo (int)$row['stock']; ?></span> available
        </div>

        <div class="product-options">
          <label for="size-<?php echo (int)$row['id']; ?>">Select Size:</label>
          <select id="size-<?php echo (int)$row['id']; ?>">
            <option value="S">Small</option>
            <option value="M">Medium</option>
            <option value="L">Large</option>
          </select>
        </div>

        <div class="product-quantity">
          <label>Quantity:</label>
          <input type="number" id="qty-<?php echo (int)$row['id']; ?>" value="1" min="1" max="<?php echo (int)$row['stock']; ?>">
        </div>

        <button class="add-to-cart" onclick="addToCart(<?php echo (int)$row['id']; ?>)">Add to Cart</button>
      </div>

    </section>
  <?php endwhile; ?>
</main>

<?php include '../includes/footer.php'; ?>

<script>
function addToCart(productId) {
  const qtyInput = document.getElementById(`qty-${productId}`);
  const qty = parseInt(qtyInput?.value || '0', 10);
  if (!qty || qty < 1) {
    alert('Quantity must be at least 1.');
    return;
  }

  fetch('../includes/update_stock.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${productId}&qty=${qty}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      const stockEl = document.getElementById(`stock-${productId}`);
      if (stockEl) stockEl.textContent = data.newStock;
      alert('Product added to cart and stock updated!');
    } else {
      alert(data.message || 'Not enough stock!');
    }
  })
  .catch(() => alert('Error adding to cart.'));
}
</script>

</body>
</html>


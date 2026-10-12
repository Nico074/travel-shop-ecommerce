<?php
session_start();
if (empty($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@travelshop.com') {
    header('Location: ../login.php');
    exit();
}

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price    = isset($_POST['price']) ? (float) $_POST['price'] : 0;
    $stock    = isset($_POST['stock']) ? (int) $_POST['stock'] : 0;
    $image    = trim($_POST['image'] ?? '');

    // Basic validation
    if ($name === '' || $category === '' || $price <= 0 || $stock < 0) {
        $error = "Invalid or missing fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (name, category, price, stock, image) VALUES (?, ?, ?, ?, ?)");
        // Types: string, string, double, int, string
        $stmt->bind_param("ssdis", $name, $category, $price, $stock, $image);

        if ($stmt->execute()) {
            header('Location: dashboard.php?success=added');
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main class="admin-form-page">
      <div class="admin-form-card">
        <div class="admin-form-header">
          <h1>Add a New Product</h1>
          <p>Keep the catalog fresh with new gear.</p>
        </div>
        <form method="POST" class="admin-form">
            <label>
              <span>Product Name</span>
              <input type="text" name="name" placeholder="Explorer Backpack" required>
            </label>

            <label>
              <span>Category</span>
              <select name="category" required>
                  <option value="">Select Category</option>
                  <option value="backpack">Backpack</option>
                  <option value="tent">Tent</option>
                  <option value="jacket">Jacket</option>
              </select>
            </label>

            <label>
              <span>Price</span>
              <input type="number" step="0.01" name="price" placeholder="79.99" required>
            </label>

            <label>
              <span>Stock Quantity</span>
              <input type="number" name="stock" placeholder="50" min="0" required>
            </label>

            <label>
              <span>Image URL (optional)</span>
              <input type="text" name="image" placeholder="https://example.com/product.jpg">
            </label>

            <button type="submit" class="btn-primary">Add Product</button>

            <?php if (isset($error)): ?>
                <div class="form-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
        </form>
      </div>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>

<?php
session_start();
if (empty($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@travelshop.com') {
    header('Location: ../login.php');
    exit();
}

require_once '../config.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: dashboard.php');
    exit();
}

// Fetch existing product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price    = isset($_POST['price']) ? (float) $_POST['price'] : 0;
    $stock    = isset($_POST['stock']) ? (int) $_POST['stock'] : 0;
    $image    = trim($_POST['image'] ?? '');

    if ($name === '' || $category === '' || $price <= 0 || $stock < 0) {
        $error = "Invalid or missing fields.";
    } else {
        $upd = $conn->prepare("UPDATE products SET name = ?, category = ?, price = ?, stock = ?, image = ? WHERE id = ?");
        $upd->bind_param("ssdisi", $name, $category, $price, $stock, $image, $id);
        $upd->execute();

        header('Location: dashboard.php?success=updated');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="admin-form-page">
      <div class="admin-form-card">
        <div class="admin-form-header">
          <h1>Edit Product</h1>
          <p>Update product details and keep the store accurate.</p>
        </div>
        <form method="POST" class="admin-form">
            <label>
              <span>Product Name</span>
              <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </label>

            <label>
              <span>Category</span>
              <select name="category" required>
                  <option value="backpack" <?php echo $product['category']=='backpack'?'selected':''; ?>>Backpack</option>
                  <option value="tent" <?php echo $product['category']=='tent'?'selected':''; ?>>Tent</option>
                  <option value="jacket" <?php echo $product['category']=='jacket'?'selected':''; ?>>Jacket</option>
              </select>
            </label>

            <label>
              <span>Price</span>
              <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </label>

            <label>
              <span>Stock Quantity</span>
              <input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" min="0" required>
            </label>

            <label>
              <span>Image URL (optional)</span>
              <input type="text" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
            </label>

            <button type="submit" class="btn-primary">Update</button>

            <?php if (isset($error)): ?>
                <div class="form-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
        </form>
      </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

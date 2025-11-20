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

// Récupère le produit existant
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
        $error = "Champs invalides ou manquants.";
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
    <style>
        form { max-width:500px; margin:50px auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 3px 8px rgba(0,0,0,0.1); }
        input, select, button { width:100%; margin:8px 0; padding:10px; }
        button { background:#00bcd4; color:white; border:none; border-radius:5px; cursor:pointer; }
        button:hover { background:#0097a7; }
        .error { color:#c00000; margin-top:10px; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <form method="POST">
        <h2>Edit Product</h2>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        <select name="category" required>
            <option value="backpack" <?php echo $product['category']=='backpack'?'selected':''; ?>>Backpack</option>
            <option value="tent" <?php echo $product['category']=='tent'?'selected':''; ?>>Tent</option>
            <option value="jacket" <?php echo $product['category']=='jacket'?'selected':''; ?>>Jacket</option>
        </select>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
        <input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" min="0" required>
        <input type="text" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
        <button type="submit">Update</button>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </form>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

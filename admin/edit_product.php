<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@travelshop.com') {
    header('Location: ../login.php');
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: dashboard.php');
    exit();
}

// Récupère le produit existant
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_POST['image'];

    $stmt = $conn->prepare("UPDATE products SET name=?, category=?, price=?, stock=?, image=? WHERE id=?");
    $stmt->bind_param("ssdssi", $name, $category, $price, $stock, $image, $id);
    $stmt->execute();

    header('Location: dashboard.php?success=updated');
    exit();
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

    <form method="POST" style="max-width:500px;margin:50px auto;">
        <h2>Edit Product</h2>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        <select name="category" required>
            <option value="backpack" <?= $product['category']=='backpack'?'selected':''; ?>>Backpack</option>
            <option value="tent" <?= $product['category']=='tent'?'selected':''; ?>>Tent</option>
            <option value="jacket" <?= $product['category']=='jacket'?'selected':''; ?>>Jacket</option>
        </select>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
        <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>
        <input type="text" name="image" value="<?= htmlspecialchars($product['image']) ?>">
        <button type="submit">Update</button>
    </form>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

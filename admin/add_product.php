<?php
session_start();
require_once '../config.php';

// Vérifie que c'est bien l'admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@travelshop.com') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = $_POST['image'];

    $stmt = $conn->prepare("INSERT INTO products (name, category, price, stock, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiss", $name, $category, $price, $stock, $image);
    
    if ($stmt->execute()) {
        header('Location: dashboard.php?success=added');
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        form {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        input, select, button {
            width: 100%;
            margin: 8px 0;
            padding: 10px;
        }
        button {
            background: #00bcd4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover { background: #0097a7; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <form method="POST">
        <h2>Add a New Product</h2>
        <input type="text" name="name" placeholder="Product Name" required>
        <select name="category" required>
            <option value="">Select Category</option>
            <option value="backpack">Backpack</option>
            <option value="tent">Tent</option>
            <option value="jacket">Jacket</option>
        </select>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <input type="number" name="stock" placeholder="Stock Quantity" required>
        <input type="text" name="image" placeholder="Image URL (optional)">
        <button type="submit">Add Product</button>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </form>
    <?php include '../includes/footer.php'; ?>
</body>
</html>

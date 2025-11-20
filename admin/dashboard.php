<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@travelshop.com') {
    header('Location: ../login.php');
    exit();
}

require_once '../config.php';

$query = "SELECT id, name, category, price, stock FROM products";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Travel Shop</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .dashboard-container { padding: 2rem; }
        .dashboard-container h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #00bcd4; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .actions button { margin-right: 5px; padding: 6px 12px; border: none; cursor: pointer; border-radius: 5px; }
        .edit-btn { background-color: #ffc107; }
        .delete-btn { background-color: #f44336; color: white; }
        .add-product { display: block; margin: 20px auto; padding: 10px 20px; background: #00bcd4; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .add-product:hover { background: #0097a7; }
        .alert { width: fit-content; margin: 10px auto 20px auto; padding: 10px 20px; border-radius: 6px; text-align: center; font-weight: 500; animation: fadeInOut 4s ease-in-out forwards; }
        .alert.success { background-color: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(-10px); }
            10% { opacity: 1; transform: translateY(0); }
            90% { opacity: 1; }
            100% { opacity: 0; transform: translateY(-10px); }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="dashboard-container">
        <h2>Admin — Product Management</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">
                <?php
                switch ($_GET['success']) {
                    case 'added':   echo "Product added successfully!"; break;
                    case 'updated': echo "Product updated successfully!"; break;
                    case 'deleted': echo "Product deleted successfully!"; break;
                    default:        echo "Action completed successfully.";
                }
                ?>
            </div>
        <?php endif; ?>

        <button class="add-product" onclick="window.location.href='add_product.php'">+ Add Product</button>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price ($)</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>

            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo (int) $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['category'] ?? 'N/A'); ?></td>
                        <td><?php echo number_format((float) $row['price'], 2); ?></td>
                        <td><?php echo (int) $row['stock']; ?></td>
                        <td class="actions">
                            <button class="edit-btn" onclick="window.location.href='edit_product.php?id=<?php echo (int) $row['id']; ?>'">Edit</button>
                            <button class="delete-btn" onclick="if(confirm('Delete this product?')) window.location.href='delete_product.php?id=<?php echo (int) $row['id']; ?>'">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No products found.</td></tr>
            <?php endif; ?>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@travelshop.com') {
    header('Location: ../login.php');
    exit();
}

require_once '../config.php';
$productsResult = $conn->query("SELECT id, name, category, price, stock FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="admin-dashboard">
      <section class="admin-grid">
        <header class="admin-grid__top">
          <div>
            <p class="eyebrow">Admin</p>
            <h1>Manage Products</h1>
            <p class="muted">Edit or delete items from the catalog.</p>
          </div>
          <div class="admin-actions">
            <button class="refresh-btn" onclick="window.location.reload()" aria-label="Refresh products">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M4 4v6h2V7.41l3.24 3.25 1.42-1.42L7.41 6H10V4H4zm16 10h-6v2h2.59l-3.25 3.24 1.42 1.42L18 17.41V20h2v-6zM6.34 17.66A7 7 0 0 1 12 5a7 7 0 0 1 4.95 2.05l1.41-1.42A9 9 0 0 0 3.5 12a8.97 8.97 0 0 0 2.63 6.36l1.41-1.41Zm11.32-11.32-1.41 1.41A7 7 0 0 1 12 19a7 7 0 0 1-4.95-2.05l-1.41 1.42A9 9 0 0 0 20.5 12a8.97 8.97 0 0 0-2.84-6.66Z" fill="currentColor"/>
              </svg>
            </button>
            <button class="btn-primary" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
          </div>
        </header>

        <div class="panel-card">
          <div class="panel-card__header">
            <h2>All Products</h2>
          </div>
          <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                  <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Category</th>
                      <th>Price ($)</th>
                      <th>Stock</th>
                      <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                <?php if ($productsResult && $productsResult->num_rows > 0): ?>
                    <?php while ($row = $productsResult->fetch_assoc()): ?>
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
                </tbody>
            </table>
          </div>
        </div>
      </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

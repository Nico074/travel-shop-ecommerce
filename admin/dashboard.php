<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@travelshop.com') {
    header('Location: ../login.php');
    exit();
}

require_once '../config.php';

// Basic stats
$stats = [
    'products'   => 0,
    'categories' => 0,
    'stock'      => 0
];

$resCount = $conn->query("SELECT COUNT(*) AS c FROM products");
if ($resCount && $row = $resCount->fetch_assoc()) {
    $stats['products'] = (int) $row['c'];
}
$resCat = $conn->query("SELECT COUNT(DISTINCT category) AS c FROM products");
if ($resCat && $row = $resCat->fetch_assoc()) {
    $stats['categories'] = (int) $row['c'];
}
$resStock = $conn->query("SELECT SUM(stock) AS s FROM products");
if ($resStock && $row = $resStock->fetch_assoc()) {
    $stats['stock'] = (int) ($row['s'] ?? 0);
}

$productsResult = $conn->query("SELECT id, name, category, price, stock FROM products ORDER BY id DESC");
$recentProducts = $conn->query("SELECT id, name, category, price, stock FROM products ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Travel Shop</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="admin-dashboard">
      <section class="admin-grid">
        <header class="admin-grid__top">
          <div>
            <p class="eyebrow">Admin</p>
            <h1>Dashboard</h1>
            <p class="muted">Track your catalog and manage products.</p>
          </div>
          <div class="admin-actions">
            <button class="refresh-btn" onclick="window.location.href='dashboard.php'" aria-label="Refresh dashboard">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M4 4v6h2V7.41l3.24 3.25 1.42-1.42L7.41 6H10V4H4zm16 10h-6v2h2.59l-3.25 3.24 1.42 1.42L18 17.41V20h2v-6zM6.34 17.66A7 7 0 0 1 12 5a7 7 0 0 1 4.95 2.05l1.41-1.42A9 9 0 0 0 3.5 12a8.97 8.97 0 0 0 2.63 6.36l1.41-1.41Zm11.32-11.32-1.41 1.41A7 7 0 0 1 12 19a7 7 0 0 1-4.95-2.05l-1.41 1.42A9 9 0 0 0 20.5 12a8.97 8.97 0 0 0-2.84-6.66Z" fill="currentColor"/>
              </svg>
            </button>
          </div>
        </header>

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

        <div class="admin-stats">
          <div class="stat-card">
            <p class="label">Total Products</p>
            <h3><?php echo $stats['products']; ?></h3>
            <span class="badge">Catalog</span>
          </div>
          <div class="stat-card">
            <p class="label">Categories</p>
            <h3><?php echo $stats['categories']; ?></h3>
            <span class="badge badge-ghost">Types</span>
          </div>
          <div class="stat-card">
            <p class="label">Units in Stock</p>
            <h3><?php echo $stats['stock']; ?></h3>
            <span class="badge badge-ghost">Inventory</span>
          </div>
        </div>

        <div class="admin-panels">
          <div class="panel-card">
            <div class="panel-card__header">
              <h2>Recent Products</h2>
              <a class="text-link" href="dashboard.php">View all</a>
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
                <?php if ($recentProducts && $recentProducts->num_rows > 0): ?>
                  <?php while ($row = $recentProducts->fetch_assoc()): ?>
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
                  <tr><td colspan="6" style="text-align:center;">No products yet.</td></tr>
                <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="panel-card">
            <h2>Quick Links</h2>
            <div class="quick-links">
              <a href="add_product.php" class="quick-link-card">
                <span class="quick-link-title">Add Product</span>
                <span class="quick-link-sub">Create a new item</span>
              </a>
              <a href="manage.php" class="quick-link-card">
                <span class="quick-link-title">Manage Products</span>
                <span class="quick-link-sub">Edit or delete items</span>
              </a>
              <a href="performance.php" class="quick-link-card">
                <span class="quick-link-title">Performance</span>
                <span class="quick-link-sub">KPIs & revenue</span>
              </a>
            </div>
          </div>
        </div>

        <div class="panel-card">
          <div class="panel-card__header">
            <h2>Sales Overview</h2>
            <span class="badge"><?php echo $stats['products'] > 0 ? '+12% MoM' : 'N/A'; ?></span>
          </div>
          <div class="chart-placeholder">
            <div class="chart-bars">
              <div style="height:70%"></div>
              <div style="height:85%"></div>
              <div style="height:60%"></div>
              <div style="height:90%"></div>
              <div style="height:75%"></div>
              <div style="height:95%"></div>
            </div>
            <p class="muted" style="text-align:center; margin-top:10px;">Fictional data - last 6 months</p>
          </div>
        </div>
      </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

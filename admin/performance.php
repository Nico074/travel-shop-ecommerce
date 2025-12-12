<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@travelshop.com') {
    header('Location: ../login.php');
    exit();
}

// Fake metrics for display
$metrics = [
    'monthly_sales' => '142',
    'monthly_revenue' => '$18,400',
    'conversion' => '2.8%',
    'avg_order' => '$129',
    'growth' => '+12% MoM'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Performance - Admin</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <?php include '../includes/header.php'; ?>

  <main class="admin-dashboard">
    <section class="admin-grid">
      <header class="admin-grid__top">
        <div>
          <p class="eyebrow">Admin</p>
          <h1>Performance</h1>
          <p class="muted">Quick snapshot of sales and revenue.</p>
        </div>
        <div class="admin-actions">
          <button class="refresh-btn" onclick="window.location.reload()" aria-label="Refresh performance">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M4 4v6h2V7.41l3.24 3.25 1.42-1.42L7.41 6H10V4H4zm16 10h-6v2h2.59l-3.25 3.24 1.42 1.42L18 17.41V20h2v-6zM6.34 17.66A7 7 0 0 1 12 5a7 7 0 0 1 4.95 2.05l1.41-1.42A9 9 0 0 0 3.5 12a8.97 8.97 0 0 0 2.63 6.36l1.41-1.41Zm11.32-11.32-1.41 1.41A7 7 0 0 1 12 19a7 7 0 0 1-4.95-2.05l-1.41 1.42A9 9 0 0 0 20.5 12a8.97 8.97 0 0 0-2.84-6.66Z" fill="currentColor"/>
            </svg>
          </button>
          <button class="btn-primary" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
        </div>
      </header>

      <div class="admin-stats">
        <div class="stat-card">
          <p class="label">Orders this month</p>
          <h3><?php echo $metrics['monthly_sales']; ?></h3>
          <span class="badge">Sales</span>
        </div>
        <div class="stat-card">
          <p class="label">Revenue this month</p>
          <h3><?php echo $metrics['monthly_revenue']; ?></h3>
          <span class="badge badge-ghost">Revenue</span>
        </div>
        <div class="stat-card">
          <p class="label">Conversion rate</p>
          <h3><?php echo $metrics['conversion']; ?></h3>
          <span class="badge badge-ghost">Traffic</span>
        </div>
        <div class="stat-card">
          <p class="label">Avg. order value</p>
          <h3><?php echo $metrics['avg_order']; ?></h3>
          <span class="badge badge-ghost">Basket</span>
        </div>
      </div>

      <div class="panel-card">
        <div class="panel-card__header">
          <h2>Sales Overview</h2>
          <span class="badge"><?php echo $metrics['growth']; ?></span>
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
          <p class="muted" style="text-align:center; margin-top:10px;">Fictional data · last 6 months</p>
        </div>
      </div>
    </section>
  </main>

  <?php include '../includes/footer.php'; ?>
</body>
</html>

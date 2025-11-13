<?php
// ✅ Démarrage sécurisé de la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Détection automatique du chemin de base selon le dossier actuel
$currentPath = $_SERVER['PHP_SELF'];

if (strpos($currentPath, '/products/') !== false) {
    $basePath = '../';
} elseif (strpos($currentPath, '/admin/') !== false) {
    $basePath = '../';
} else {
    $basePath = '';
}
?>

<!-- 🌍 Navbar -->
<nav class="navbar">
  <div class="logo">Lpa Travel Shop</div>

  <ul class="menu">
    <li><a href="<?php echo $basePath; ?>index.php">Home</a></li>
    <li class="dropdown">
      <a href="#">Shop ▼</a>
      <ul class="dropdown-menu">
        <li><a href="<?php echo $basePath; ?>products/backpacks.php">Backpacks</a></li>
        <li><a href="<?php echo $basePath; ?>products/tents.php">Tents</a></li>
        <li><a href="<?php echo $basePath; ?>products/jackets.php">Jackets</a></li>
      </ul>
    </li>
    <li><a href="<?php echo $basePath; ?>contact.php">Contact</a></li>
  </ul>

  <div class="search-bar">
    <input type="text" id="searchInput" placeholder="Search products...">
    <button onclick="searchProducts()">🔍</button>
  </div>

  <div class="actions">
    <?php if (isset($_SESSION['user_name'])): ?>
      👋 Hello, <?= htmlspecialchars($_SESSION['user_name']); ?>
      <?php if (isset($_SESSION['user_email']) && $_SESSION['user_email'] === 'admin@travelshop.com'): ?>
          <strong>(admin)</strong>
          <a href="<?php echo $basePath; ?>admin/dashboard.php" class="btn-admin">⚙️ Admin Panel</a>
      <?php endif; ?>
      <a href="<?php echo $basePath; ?>logout.php" class="btn-logout">Logout</a>
    <?php else: ?>
      <a href="<?php echo $basePath; ?>login.php" class="account-link">Login</a>
    <?php endif; ?>
    <button id="cart-btn" class="cart-icon">🛒</button>
  </div>
</nav>

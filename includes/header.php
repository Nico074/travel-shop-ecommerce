<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base path fixe (configurable via APP_BASE_PATH si besoin)
$basePath = $_ENV['APP_BASE_PATH'] ?? '/lpa_travel_shop/';

$cartCount = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0;
?>
<header>
  <nav class="navbar">
    <div class="logo">Lpa Travel Shop</div>

    <ul class="menu">
      <li><a href="<?php echo $basePath; ?>index.php">Home</a></li>

      <li class="dropdown">
        <a href="#">Shop</a>
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
      <button type="button" onclick="if (typeof searchProducts === 'function') { searchProducts(); } else { alert('Search unavailable on this page.'); }">Search</button>
    </div>

    <div class="actions">
      <?php if (!empty($_SESSION['user_name'])): ?>
         <span>Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>

         <?php if (!empty($_SESSION['user_email']) && $_SESSION['user_email'] === 'admin@travelshop.com'): ?>
              <a href="<?php echo $basePath; ?>admin/dashboard.php" class="btn-admin">Admin Panel</a>
         <?php endif; ?>

         <a href="<?php echo $basePath; ?>logout.php" class="btn-logout">Logout</a>
      <?php else: ?>
         <a href="<?php echo $basePath; ?>login.php" class="account-link">Login</a>
      <?php endif; ?>

      <a href="<?php echo $basePath; ?>cart.php" class="cart-icon" id="cart-btn">
          🛒
          <?php if ($cartCount > 0): ?>
              <span class="cart-count"><?php echo $cartCount; ?></span>
          <?php endif; ?>
      </a>
    </div>
  </nav>
</header>


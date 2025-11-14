<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$basePath = "/lpa_travel_shop/";
?>

<header>
  <nav class="navbar">
    <div class="logo">Lpa Travel Shop</div>

    <ul class="menu">
      <li><a href="<?php echo $basePath; ?>index.php">Home</a></li>

      <li class="dropdown">
        <a href="#">Shop ▾</a>
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
              <a href="<?php echo $basePath; ?>admin/dashboard.php" class="btn-admin">⚙ Admin Panel</a>
         <?php endif; ?>

         <a href="<?php echo $basePath; ?>logout.php" class="btn-logout">Logout</a>

      <?php else: ?>
         <a href="<?php echo $basePath; ?>login.php" class="account-link">Login</a>
      <?php endif; ?>

      <!-- CART BUTTON always visible -->
<?php
$cartCount = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0;
?>
<a href="<?php echo $basePath; ?>cart.php" class="cart-icon" id="cart-btn">
    🛒
    <?php if ($cartCount > 0): ?>
        <span class="cart-count"><?php echo $cartCount; ?></span>
    <?php endif; ?>
</a>
    </div>
  </nav>
</header>


<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fixed base path (configurable via APP_BASE_PATH if needed)
$basePath = $_ENV['APP_BASE_PATH'] ?? '/lpa_travel_shop/';

$cartCount = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0;
$isAdmin = !empty($_SESSION['user_email']) && $_SESSION['user_email'] === 'admin@travelshop.com';
$isHomePage = basename($_SERVER['SCRIPT_NAME'] ?? '') === 'index.php';
$shouldShowGreeting = !empty($_SESSION['user_name']) && $isHomePage;
$loginRole = $_SESSION['login_success'] ?? null;
$welcomeOnce = $_SESSION['welcome_once'] ?? null;
if (isset($_SESSION['login_success'])) {
    unset($_SESSION['login_success']);
}
if (isset($_SESSION['welcome_once'])) {
    unset($_SESSION['welcome_once']);
}
?>
<header>
  <nav class="navbar">
    <a class="logo" href="<?php echo $basePath; ?>index.php">Lpa Travel Shop</a>

    <ul class="menu">
      <li><a href="<?php echo $basePath; ?>index.php">Home</a></li>

      <li class="dropdown">
        <a href="#" onclick="return goRandomShop();">Shop</a>
        <ul class="dropdown-menu">
          <li><a href="<?php echo $basePath; ?>products/backpacks.php">Backpacks</a></li>
          <li><a href="<?php echo $basePath; ?>products/tents.php">Tents</a></li>
          <li><a href="<?php echo $basePath; ?>products/jackets.php">Jackets</a></li>
        </ul>
      </li>

      <li><a href="<?php echo $basePath; ?>contact.php">Contact</a></li>
      <li><a href="<?php echo $basePath; ?>about_us.php">About Us</a></li>
    </ul>

    <form class="search-bar" method="get" action="<?php echo $basePath; ?>search.php">
      <input type="text" id="searchInput" name="q" placeholder="Search products..." aria-label="Search products">
      <button type="submit">Search</button>
    </form>

    <?php if ($isAdmin): ?>
      <a href="<?php echo $basePath; ?>admin/dashboard.php" class="btn-admin btn-admin-inline">Admin Panel</a>
    <?php endif; ?>

    <div class="actions">
      <?php if (!empty($_SESSION['user_name'])): ?>
        <?php if ($shouldShowGreeting): ?>
         <span class="greeting greeting-inline" id="user-greeting">
           <?php if ($isAdmin): ?>
             <svg class="greet-icon" viewBox="0 0 24 24" aria-hidden="true">
               <path d="M12 8.5A3.5 3.5 0 1 0 12 15.5 3.5 3.5 0 0 0 12 8.5Zm0-6 1.1 2.3a7.2 7.2 0 0 1 2.5.9L17.9 3l1.8 1.8-1.7 2.3a7.2 7.2 0 0 1 .9 2.5L21 12l-2.3 1.1a7.2 7.2 0 0 1-.9 2.5l1.7 2.3-1.8 1.8-2.3-1.7a7.2 7.2 0 0 1-2.5.9L12 21l-1.1-2.3a7.2 7.2 0 0 1-2.5-.9L6.1 20.9 4.3 19l1.7-2.3a7.2 7.2 0 0 1-.9-2.5L3 12l2.3-1.1a7.2 7.2 0 0 1 .9-2.5L4.3 6.1 6.1 4.3l2.3 1.7a7.2 7.2 0 0 1 2.5-.9Z" fill="currentColor"/>
             </svg>
             Welcome, Admin
           <?php else: ?>
             <svg class="greet-icon" viewBox="0 0 24 24" aria-hidden="true">
               <circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.6"/>
               <circle cx="9" cy="10" r="1.2" fill="currentColor"/>
               <circle cx="15" cy="10" r="1.2" fill="currentColor"/>
               <path d="M8.5 14.2c.6 1 1.9 1.8 3.5 1.8s2.9-.7 3.5-1.8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" fill="none"/>
             </svg>
             Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
           <?php endif; ?>
         </span>
        <?php endif; ?>

         <a href="<?php echo $basePath; ?>logout.php" class="btn-logout">Logout</a>
      <?php else: ?>
         <a href="<?php echo $basePath; ?>login.php" class="account-link">Login</a>
      <?php endif; ?>

      <a href="<?php echo $basePath; ?>cart.php" class="cart-icon" id="cart-btn" aria-label="Cart">
          <svg class="cart-svg" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M3 5h2l1.6 9.2a1 1 0 0 0 1 .8h7.8a1 1 0 0 0 1-.8L17.5 8H6.1" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="9" cy="19" r="1.2" fill="currentColor"/>
            <circle cx="15" cy="19" r="1.2" fill="currentColor"/>
          </svg>
          <?php if ($cartCount > 0): ?>
              <span class="cart-count"><?php echo $cartCount; ?></span>
          <?php endif; ?>
      </a>
    </div>
</nav>
</header>

<script>
function goRandomShop() {
  const base = "<?php echo $basePath; ?>";
  const pages = [
    base + "products/backpacks.php",
    base + "products/tents.php",
    base + "products/jackets.php"
  ];
  const pick = pages[Math.floor(Math.random() * pages.length)];
  window.location.href = pick;
  return false; // prevent empty anchor
}
</script>

<?php if ($loginRole): ?>
  <div class="login-toast" id="login-toast">
    <?php echo $loginRole === 'admin' ? 'Admin login successful.' : 'Login successful.'; ?>
  </div>
<?php endif; ?>

<?php if ($welcomeOnce): ?>
  <div class="welcome-toast" id="welcome-toast">
    <?php echo ($welcomeOnce === 'admin') ? 'Welcome, Admin' : 'Welcome, ' . htmlspecialchars($_SESSION['user_name']); ?>
  </div>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const greeting = document.getElementById("user-greeting");
  if (greeting) {
    setTimeout(() => greeting.classList.add("hide"), 10000);
  }

  const toast = document.getElementById("login-toast");
  if (toast) {
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 2000);
  }

  const welcome = document.getElementById("welcome-toast");
  if (welcome) {
    welcome.classList.add("show");
    setTimeout(() => welcome.classList.remove("show"), 4000);
  }

  const logoutMessages = document.querySelectorAll(".logout-message");
  logoutMessages.forEach(msg => {
    setTimeout(() => {
      msg.classList.add("fade-out");
      setTimeout(() => { msg.style.display = "none"; }, 400);
    }, 5000);
  });
});
</script>

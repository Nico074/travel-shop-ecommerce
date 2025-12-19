<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, redirect
if (isset($_SESSION['user_email'])) {
    if ($_SESSION['user_email'] === 'admin@travelshop.com') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Travel Shop</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

<main class="auth-container">
  <div class="auth-card">
    <h2>Welcome to Travel Shop</h2>
    <p>Please login or create an account</p>

    <?php
    if (isset($_SESSION["error"])) {
      echo "<div class='error-message'>" . htmlspecialchars($_SESSION["error"]) . "</div>";
      unset($_SESSION["error"]);
    }
    if (isset($_SESSION["success"])) {
      echo "<div class='success-message'>" . htmlspecialchars($_SESSION["success"]) . "</div>";
      unset($_SESSION["success"]);
    }
    if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
      echo "<div class='success-message logout-message'>You have been logged out successfully.</div>";
    }
    ?>

    <div class="auth-tabs">
      <button class="tab-btn active" onclick="showForm(event,'login')">Login</button>
      <button class="tab-btn" onclick="showForm(event,'signup')">Sign Up</button>
    </div>

    <form id="login-form" class="auth-form" action="check_login.php" method="POST">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn-auth">Login</button>
    </form>

    <form id="signup-form" class="auth-form hidden" action="signup.php" method="POST">
      <input type="text" name="username" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button type="submit" class="btn-auth">Create Account</button>
    </form>
  </div>
</main>

<script src="js/app.js"></script>
<script>
  function showForm(e, form) {
    e.preventDefault();
    document.querySelectorAll(".auth-form").forEach(f => f.classList.add("hidden"));
    document.querySelectorAll(".tab-btn").forEach(b => b.classList.remove("active"));
    document.getElementById(form + "-form").classList.remove("hidden");
    e.target.classList.add("active");
  }
</script>
<?php include 'includes/footer.php'; ?>
</body>
</html>

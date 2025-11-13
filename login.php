<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Si déjà connecté, on redirige vers la bonne page :
if (isset($_SESSION['user_email'])) {
    if ($_SESSION['user_email'] === 'admin@travelshop.com') {
        header("Location: admin/dashboard.php");
        exit();
    } else {
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Travel Shop</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

<!-- Login Form -->
<main class="auth-container">
  <div class="auth-card">
    <h2>Welcome to Travel Shop</h2>
    <p>Please login or create an account</p>

    <!-- ✅ Message d'erreur affiché ici -->
    <?php
    if (isset($_SESSION["error"])) {
      echo "<div class='error-message'>" . $_SESSION["error"] . "</div>";
      unset($_SESSION["error"]);
    }
    if (isset($_SESSION["success"])) {
      echo "<div class='success-message'>" . $_SESSION["success"] . "</div>";
      unset($_SESSION["success"]);
    }
    ?>

    <!-- Tabs -->
    <div class="auth-tabs">
      <button class="tab-btn active" onclick="showForm('login')">Login</button>
      <button class="tab-btn" onclick="showForm('signup')">Sign Up</button>
    </div>

    <!-- Login Form -->
    <form id="login-form" class="auth-form" action="check_login.php" method="POST">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn-auth">Login</button>
    </form>

    <!-- Signup Form -->
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
  // Toggle between login/signup
  function showForm(form) {
    document.querySelectorAll(".auth-form").forEach(f => f.classList.add("hidden"));
    document.querySelectorAll(".tab-btn").forEach(b => b.classList.remove("active"));
    document.getElementById(form + "-form").classList.remove("hidden");
    event.target.classList.add("active");
  }
</script>
<?php include 'includes/footer.php'; ?>
</body>
</html>


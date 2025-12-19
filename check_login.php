<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($email === '' || $password === '') {
    $_SESSION['error'] = "Email or password is missing.";
    header("Location: login.php");
    exit;
}

// Look up the user
$stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['error'] = "No account found with this email.";
    header("Location: login.php");
    exit;
}

$user = $result->fetch_assoc();

// Verify the password
if (!password_verify($password, $user['password'])) {
    $_SESSION['error'] = "Invalid password.";
    header("Location: login.php");
    exit;
}

// Auth OK: store session
$_SESSION['user_id']    = (int) $user['id'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_name']  = $user['username'];
$_SESSION['login_success'] = ($user['email'] === 'admin@travelshop.com') ? 'admin' : 'customer';
$_SESSION['welcome_once']  = ($user['email'] === 'admin@travelshop.com') ? 'admin' : 'customer';

// Redirect admin vs user
if ($user['email'] === 'admin@travelshop.com') {
    header("Location: ./admin/dashboard.php");
} else {
    header("Location: ./index.php");
}
exit;

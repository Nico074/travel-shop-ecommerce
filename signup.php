<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

require_once 'config.php';

$username  = trim($_POST['username'] ?? '');
$email     = trim($_POST['email'] ?? '');
$password  = $_POST['password'] ?? '';
$confirm   = $_POST['confirm_password'] ?? '';

// Basic validation
if ($username === '' || $email === '' || $password === '' || $confirm === '') {
    $_SESSION['error'] = "All fields are required.";
    header("Location: login.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email.";
    header("Location: login.php");
    exit;
}

if ($password !== $confirm) {
    $_SESSION['error'] = "Passwords do not match.";
    header("Location: login.php");
    exit;
}

// Check if the email already exists
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$exists = $check->get_result();
if ($exists->num_rows > 0) {
    $_SESSION['error'] = "An account already exists with this email.";
    header("Location: login.php");
    exit;
}

// Create the account
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hashedPassword);

if ($stmt->execute()) {
    $_SESSION['success'] = "Account created successfully. You can now log in.";
} else {
    $_SESSION['error'] = "Error creating the account.";
}

header("Location: login.php");
exit;

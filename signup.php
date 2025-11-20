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

// Validations de base
if ($username === '' || $email === '' || $password === '' || $confirm === '') {
    $_SESSION['error'] = "Tous les champs sont obligatoires.";
    header("Location: login.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Email invalide.";
    header("Location: login.php");
    exit;
}

if ($password !== $confirm) {
    $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
    header("Location: login.php");
    exit;
}

// Vérifie si l'email existe déjà
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$exists = $check->get_result();
if ($exists->num_rows > 0) {
    $_SESSION['error'] = "Un compte existe déjà avec cet email.";
    header("Location: login.php");
    exit;
}

// Création du compte
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hashedPassword);

if ($stmt->execute()) {
    $_SESSION['success'] = "Compte créé avec succès, vous pouvez vous connecter.";
} else {
    $_SESSION['error'] = "Erreur lors de la création du compte.";
}

header("Location: login.php");
exit;


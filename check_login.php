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
    $_SESSION['error'] = "Email ou mot de passe manquant.";
    header("Location: login.php");
    exit;
}

// Recherche de l'utilisateur
$stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['error'] = "Aucun compte trouvé avec cet email.";
    header("Location: login.php");
    exit;
}

$user = $result->fetch_assoc();

// Vérification du mot de passe
if (!password_verify($password, $user['password'])) {
    $_SESSION['error'] = "Mot de passe invalide.";
    header("Location: login.php");
    exit;
}

// Auth OK : on enregistre la session
$_SESSION['user_id']    = (int) $user['id'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_name']  = $user['username'];

// Redirection admin vs utilisateur
if ($user['email'] === 'admin@travelshop.com') {
    header("Location: ./admin/dashboard.php");
} else {
    header("Location: ./index.php");
}
exit;




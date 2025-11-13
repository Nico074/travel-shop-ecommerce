<?php
// --- Démarre la session ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Supprime toutes les variables de session ---
$_SESSION = [];

// --- Détruit la session ---
session_unset();
session_destroy();

// --- Redirige vers la page de login ---
header("Location: login.php");
exit();

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php'; // Connexion à la base

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // 🔍 Vérifie si l'utilisateur existe
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // 🔑 Vérifie le mot de passe
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['username'];

            // ✅ Si c’est l’admin
            if ($user['email'] === 'admin@travelshop.com') {
                header("Location: ./admin/dashboard.php");
                exit();
            } else {
                header("Location: ./index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "❌ Invalid password!";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "⚠️ No account found with that email!";
        header("Location: login.php");
        exit();
    }
} else {
    // 🚫 Empêche l’accès direct au script
    header("Location: login.php");
    exit();
}
?>



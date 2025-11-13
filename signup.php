<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];

    // Vérification si les mots de passe correspondent
    if ($password !== $confirm) {
        $error = "Passwords do not match!";
    } else {
        // Hachage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertion sécurisée
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            $success = "Account created successfully! You can now log in.";
        } else {
            $error = "Error: " . $stmt->error;
        }
    }
}
?>

<!-- Message de succès ou d'erreur -->
<?php if (isset($error)): ?>
    <div class="error-message"><?= htmlspecialchars($error) ?></div>
<?php elseif (isset($success)): ?>
    <div class="success-message"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>


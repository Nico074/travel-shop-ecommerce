<?php
session_start();
if (empty($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@travelshop.com') {
    header('Location: ../login.php');
    exit();
}

require_once '../config.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    try {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        // The product is part of an existing order (foreign key), so it cannot be deleted
        header('Location: dashboard.php?error=in_order');
        exit();
    }
}

header('Location: dashboard.php?success=deleted');
exit();

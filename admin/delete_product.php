<?php
session_start();
if (empty($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@travelshop.com') {
    header('Location: ../login.php');
    exit();
}

require_once '../config.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('Location: dashboard.php?success=deleted');
exit();

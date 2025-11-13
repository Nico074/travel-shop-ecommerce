<?php
include '../config.php';

if (isset($_POST['id']) && isset($_POST['qty'])) {
  $id = intval($_POST['id']);
  $qty = intval($_POST['qty']);

  $query = "SELECT stock FROM products WHERE id = $id";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $stock = $row['stock'];

  if ($stock >= $qty) {
    $newStock = $stock - $qty;
    mysqli_query($conn, "UPDATE products SET stock = $newStock WHERE id = $id");
    echo json_encode(['success' => true, 'newStock' => $newStock]);
  } else {
    echo json_encode(['success' => false]);
  }
}
?>

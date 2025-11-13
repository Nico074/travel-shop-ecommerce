<?php
$host = "localhost";
$user = "root"; // identifiant par défaut de XAMPP
$pass = "";     // mot de passe vide par défaut
$db   = "lpa_travelshop_db";

// Connexion
$conn = new mysqli($host, $user, $pass, $db);

// Vérification
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
$servername = "localhost";
$username = "root";
$password = ""; // ou ton mot de passe si tu en as un
$dbname = "lpa_travelshop_db"; // ta base déjà existante

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>


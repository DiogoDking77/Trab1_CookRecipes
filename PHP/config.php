<?php
$host = "localhost:3308";
$user = "root";
$password = "";
$database = "lethimcook";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>

<?php
$host = 'localhost';
$db = 'galeria_db';
$user = 'gcortez';
$pass = '12345';

try {
    // CAMBIA $conexion por $pdo
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>

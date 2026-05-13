<?php
header('Content-Type: application/json');
include 'conexion.php';

try {
    $query    = $pdo->query("SELECT id, nombre, ruta FROM imagenes ORDER BY id ASC");
    $imagenes = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($imagenes);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>

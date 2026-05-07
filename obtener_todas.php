<?php
include 'conexion.php'; // Asegúrate de que este use 'galeria_db'

$sql = "SELECT * FROM imagenes";
$resultado = mysqli_query($conexion, $sql);
$fotos = [];

while($fila = mysqli_fetch_assoc($resultado)) {
    $fotos[] = $fila;
}

echo json_encode($fotos);
?>
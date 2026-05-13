<?php
include 'conexion.php';

$direccion = isset($_GET['direccion']) ? $_GET['direccion'] : 'next';
$actualId = isset($_GET['actual']) ? (int)$_GET['actual'] : 0;

if ($direccion == 'next') {
    // Buscar el ID mayor más cercano
    $sql = "SELECT id, nombre, ruta FROM imagenes WHERE id > $actualId ORDER BY id ASC LIMIT 1";
} else {
    // Buscar el ID menor más cercano
    $sql = "SELECT id, nombre, ruta FROM imagenes WHERE id < $actualId ORDER BY id DESC LIMIT 1";
}

$resultado = mysqli_query($conn, $sql);

// Si no hay más resultados, volvemos al inicio o final (Ciclo Infinito)
if (mysqli_num_rows($resultado) == 0) {
    if ($direccion == 'next') {
        $sql_reset = "SELECT id, nombre, ruta FROM imagenes ORDER BY id ASC LIMIT 1";
    } else {
        $sql_reset = "SELECT id, nombre, ruta FROM imagenes ORDER BY id DESC LIMIT 1";
    }
    $resultado = mysqli_query($conn, $sql_reset);
}

$datos = mysqli_fetch_assoc($resultado);

// Retornar JSON
if ($datos) {
    echo json_encode($datos);
} else {
    echo json_encode(['error' => 'No hay imágenes']);
}

mysqli_close($conn);
?>
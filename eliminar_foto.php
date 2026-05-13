<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    exit('No autorizado');
}

if (isset($_POST['id']) && isset($_POST['ruta'])) {
    $id = mysqli_real_escape_string($conexion, $_POST['id']);
    $ruta = mysqli_real_escape_string($conexion, $_POST['ruta']);

    // 1. Intentar borrar el archivo físico
    if (file_exists($ruta)) {
        unlink($ruta);
    }

    // 2. Borrar de la base de datos
    $sql = "DELETE FROM imagenes WHERE id = '$id'";
    
    if (mysqli_query($conexion, $sql)) {
        echo "success";
    } else {
        echo mysqli_error($conexion);
    }
}
?>
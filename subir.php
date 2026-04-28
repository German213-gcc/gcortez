<?php
include 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $foto = $_FILES['foto'];
    $carpeta = "uploads/";
    if (!file_exists($carpeta)) { mkdir($carpeta, 0777, true); }
    $ruta_final = $carpeta . time() . "_" . $foto['name'];
    if (move_uploaded_file($foto['tmp_name'], $ruta_final)) {
        $sql = "INSERT INTO imagenes (nombre, ruta) VALUES ('$nombre', '$ruta_final')";
        if (mysqli_query($conn, $sql)) { echo "Imagen guardada correctamente."; }
    } else { echo "Error al subir archivo."; }
}
mysqli_close($conn);
?>
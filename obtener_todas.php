<?php
include 'conexion.php';
$sql = "SELECT id, nombre, ruta FROM imagenes ORDER BY id ASC";
$resultado = mysqli_query($conn, $sql);
$imagenes = [];
while ($row = mysqli_fetch_assoc($resultado)) {
    $imagenes[] = $row;
}
echo json_encode($imagenes);
mysqli_close($conn);
?>
<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validamos que los campos no vengan vacíos
    $nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $correo   = mysqli_real_escape_string($conexion, $_POST['correo']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre, correo, password) VALUES ('$nombre', '$correo', '$password')";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('¡Cuenta creada con éxito! Inicia sesión.'); window.location='login.php';</script>";
    } else {
        echo "Error al crear cuenta: " . mysqli_error($conexion);
    }
}
?>
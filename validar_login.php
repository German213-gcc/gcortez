<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpiamos los datos de entrada
    $correo   = mysqli_real_escape_string($conexion, $_POST['correo']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $resultado = mysqli_query($conexion, $sql);
    $usuario   = mysqli_fetch_assoc($resultado);

    // Verificamos si el usuario existe y si la contraseña coincide con el hash de la DB
    if ($usuario && password_verify($password, $usuario['password'])) {
        
        // Creamos la sesión usando el nombre que aparece en tu captura (GERMAN o cortez)
        $_SESSION['usuario'] = $usuario['nombre']; 
        
        // CRÍTICO: Guardamos la sesión físicamente antes del redireccionamiento
        session_write_close(); 
        
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Correo o contraseña incorrectos'); window.location='login.php';</script>";
    }
}
?>
<?php
session_start();
include 'conexion.php'; // Aquí ya viene definido $pdo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Obtenemos los datos del formulario
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        // 2. Buscamos al usuario por correo usando PDO
        $sql = "SELECT * FROM usuarios WHERE correo = :correo";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':correo' => $correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // 3. Verificamos si existe el usuario y la contraseña coincide
        // Nota: Si en tu base de datos la contraseña NO está encriptada, usa: if ($usuario && $password == $usuario['password'])
        if ($usuario && password_verify($password, $usuario['password'])) {
            
            // Creamos la sesión
            $_SESSION['usuario'] = $usuario['nombre'];
            
            // Guardamos sesión antes de redireccionar
            session_write_close();
            
            header("Location: index.php");
            exit();
        } else {
            // Si no coincide, mandamos alerta
            echo "<script>alert('Correo o contraseña incorrectos'); window.location='login.php';</script>";
        }

    } catch (PDOException $e) {
        // Si hay error de SQL, lo mostramos para saber qué pasa
        die("Error en la base de datos: " . $e->getMessage());
    }
}
?>

<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';

    // Encriptamos la contraseña por seguridad (esto es lo ideal)
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // 1. Preparamos la consulta con los nombres exactos de tu tabla
        $sql = "INSERT INTO usuarios (nombre, correo, password) VALUES (:nombre, :correo, :password)";
        $stmt = $pdo->prepare($sql);
        
        // 2. Ejecutamos pasando los datos
        $stmt->execute([
            ':nombre'   => $nombre,
            ':correo'   => $correo,
            ':password' => $password_hash
        ]);

        // 3. Si todo sale bien, mandamos al login
        echo "<script>
                alert('¡Cuenta creada con éxito!');
                window.location.href='login.php';
              </script>";

    } catch (PDOException $e) {
        // Si sale error, aquí nos dirá exactamente por qué
        http_response_code(500);
        echo "Error en el registro: " . $e->getMessage();
    }
}
?>

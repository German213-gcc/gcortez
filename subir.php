<?php
session_start();
include 'conexion.php'; 

// 1. Verificamos que se haya enviado el formulario por POST y exista el archivo 'foto'
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['foto'])) {
    
    // 2. Limpiamos el nombre para evitar inyecciones SQL
    $nombre_imagen = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $archivo = $_FILES['foto']; 
    
    // 3. Definimos el directorio de subida
    $directorio = 'uploads/';
    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true);
    }

    // 4. Creamos un nombre único para el archivo basado en el tiempo
    $nombre_final = time() . "_" . basename($archivo['name']);
    $ruta_completa = $directorio . $nombre_final;

    // 5. Intentamos mover el archivo físico al servidor
    if (move_uploaded_file($archivo['tmp_name'], $ruta_completa)) {
        
        // 6. Insertamos en la tabla 'imagenes' de 'galeria_db'
        $sql = "INSERT INTO imagenes (nombre, ruta) VALUES ('$nombre_imagen', '$ruta_completa')";
        
        if (mysqli_query($conexion, $sql)) {
            // Esto es lo que espera tu AJAX en index.php para mostrar el check verde
            echo "success"; 
        } else {
            // Si falla la DB, mandamos código de error 500
            http_response_code(500);
            echo "Error en la base de datos: " . mysqli_error($conexion);
        }
    } else {
        // Si falla el movimiento del archivo físico
        http_response_code(500);
        echo "Error: No se pudo mover el archivo a la carpeta uploads.";
    }
} else {
    http_response_code(400);
    echo "Petición inválida.";
}
?>
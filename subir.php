<?php
session_start();
include 'conexion.php';    // MariaDB
include 'conexion_pg.php'; // PostgreSQL (Con el timeout de 1s)

// Ruta absoluta de la carpeta en Linux
$dir_subida = "/var/www/gcortez/uploads/";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
    
    // 1. Recibir nombre del formulario (si está vacío usa el original)
    $nombre_visual = !empty($_POST['nombre_personalizado']) ? $_POST['nombre_personalizado'] : basename($_FILES["foto"]["name"]);
    
    // 2. Generar nombre de archivo único para el disco duro
    $extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
    $nombre_archivo_fisico = time() . "_" . bin2hex(random_bytes(4)) . "." . $extension;
    
    $ruta_completa = $dir_subida . $nombre_archivo_fisico;
    $ruta_para_db = "uploads/" . $nombre_archivo_fisico;

    // 3. Mover el archivo de la carpeta temporal a la final
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $ruta_completa)) {
        try {
            // --- MARIADB: Guardar datos de la imagen ---
            $stmt = $pdo->prepare("INSERT INTO imagenes (nombre, ruta) VALUES (?, ?)");
            $stmt->execute([$nombre_visual, $ruta_para_db]);

            // --- POSTGRESQL: Registrar historial (si está activo) ---
            if ($pdo_pg !== null) {
                try {
                    $st_pg = $pdo_pg->prepare("INSERT INTO historial_visitas (usuario) VALUES (?)");
                    $st_pg->execute([$_SESSION['usuario'] ?? 'Sistema']);
                } catch (Exception $e) {
                    // Fallo silencioso en Postgres para no trabar la web
                }
            }

            // Redirigir al dashboard con éxito
            header("Location: index.php?status=success");
            exit();

        } catch (Exception $e) {
            die("Error en Base de Datos: " . $e->getMessage());
        }
    } else {
        die("Error: No se pudo mover el archivo. Revisa permisos de carpeta.");
    }
} else {
    header("Location: index.php");
}
?>

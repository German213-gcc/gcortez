<?php
session_start();
include 'conexion.php';    // Verifica que aquí se cree $pdo
include 'conexion_pg.php'; // Verifica que aquí se cree $pdo_pg

// Cambiamos 'imagen' por el nombre que uses en tu HTML (revisa si es 'foto' o 'imagen')
$campo_archivo = isset($_FILES['imagen']) ? 'imagen' : (isset($_FILES['foto']) ? 'foto' : null);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $campo_archivo) {
    // 1. Recibir datos
    $nombre_visual = !empty($_POST['nombre']) ? $_POST['nombre'] : $_FILES[$campo_archivo]["name"];
    $usuario = $_SESSION['usuario'] ?? 'Sistema';
    
    // 2. Rutas
    $directorio = "uploads/";
    $nombre_archivo_fisico = time() . "_" . basename($_FILES[$campo_archivo]["name"]);
    $ruta_completa = $directorio . $nombre_archivo_fisico;

    // 3. Mover archivo
    if (move_uploaded_file($_FILES[$campo_archivo]["tmp_name"], $ruta_completa)) {
        try {
            // --- MARIADB ---
            $stmt = $pdo->prepare("INSERT INTO imagenes (nombre, ruta) VALUES (?, ?)");
            $stmt->execute([$nombre_visual, $ruta_completa]);

            // --- POSTGRESQL ---
            if (isset($pdo_pg)) {
                $accion = "Subio imagen: " . $nombre_visual;
                $st_pg = $pdo_pg->prepare("INSERT INTO historial_visitas (usuario, accion) VALUES (?, ?)");
                $st_pg->execute([$usuario, $accion]);
            }

            header("Location: index.php?status=success");
            exit();

        } catch (Exception $e) {
            die("Error en BD: " . $e->getMessage());
        }
    } else {
        die("Error: No se pudo mover el archivo. Revisa que la carpeta 'uploads' tenga permisos 777.");
    }
} else {
    // Si llega aquí, es porque no encontró el archivo en el formulario
    die("Error: No se recibió ninguna imagen. Revisa el 'name' de tu input en el HTML.");
}
?>

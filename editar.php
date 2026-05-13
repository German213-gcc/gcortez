<?php
include 'conexion.php';

if (isset($_GET['id']) && isset($_GET['nombre'])) {
    $id = $_GET['id'];
    $nombre = $_GET['nombre'];

    try {
        // Actualizamos el nombre en MariaDB
        $stmt = $pdo->prepare("UPDATE imagenes SET nombre = ? WHERE id = ?");
        $stmt->execute([$nombre, $id]);
        
        // Si usas PostgreSQL también, aquí podrías duplicar la lógica
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Regresamos al visor automáticamente
header("Location: visor.php");
exit();
?>

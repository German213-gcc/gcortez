<?php
include 'conexion.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    
    // 1. Buscamos la ruta de la foto para borrar el archivo físico
    $stmt = $pdo->prepare("SELECT ruta FROM imagenes WHERE id = ?");
    $stmt->execute([$id]);
    $foto = $stmt->fetch();
    
    if ($foto) {
        if (file_exists($foto['ruta'])) {
            unlink($foto['ruta']); // Borra el archivo
        }
        
        // 2. Borra el registro de la base de datos
        $del = $pdo->prepare("DELETE FROM imagenes WHERE id = ?");
        $del->execute([$id]);
    }
    
    header("Location: visor.php");
}
?>

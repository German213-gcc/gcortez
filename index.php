<?php
session_start();
// Seguridad: Si no hay sesión, redirigir al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';    // MariaDB
include 'conexion_pg.php'; // PostgreSQL

// Obtener lista de imágenes de MariaDB
try {
    $query = $pdo->query("SELECT * FROM imagenes ORDER BY id DESC");
    $imagenes = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $imagenes = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Imágenes | TESVG 2026</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #0d1117; color: #c9d1d9; font-family: 'Segoe UI', sans-serif; }
        .sidebar { background: #0d1117; border-right: 1px solid #30363d; height: 100vh; position: fixed; width: 240px; }
        .main-content { margin-left: 240px; padding: 30px; }
        .card-custom { background: #161b22; border: 1px solid #30363d; border-radius: 12px; padding: 25px; }
        .nav-link { color: #8b949e; padding: 12px; display: block; text-decoration: none; border-radius: 8px; margin-bottom: 5px; }
        .nav-link.active { background: #1f2937; color: #3fb950; border-left: 3px solid #3fb950; }
        .btn-logout { color: #f85149; position: absolute; bottom: 30px; left: 25px; text-decoration: none; font-size: 14px; }
        .img-item { background: #0d1117; border: 1px solid #30363d; border-radius: 10px; padding: 12px; margin-bottom: 12px; }
        .btn-abrir-visor { background: #1f2937; border: 1px solid #30363d; color: #3fb950; border-radius: 8px; padding: 6px 16px; text-decoration: none; font-size: 14px; }
        /* Estilo para que las letras se vean claras */
        label.form-label-custom { color: #ffffff !important; font-weight: bold; font-size: 13px; }
    </style>
</head>
<body>

    <div class="sidebar p-3">
        <h4 class="text-success fw-bold mb-0">TESVG</h4>
        <small class="text-muted d-block mb-4">Ing. Sistemas · <?php echo htmlspecialchars($_SESSION['usuario']); ?></small>
        <nav>
            <a href="index.php" class="nav-link active"><i class="bi bi-grid me-2"></i> Dashboard</a>
            <a href="visor.php" class="nav-link"><i class="bi bi-eye me-2"></i> Visor</a>
        </nav>
        <a href="logout.php" class="btn-logout"><i class="bi bi-box-arrow-left me-2"></i> Cerrar sesión</a>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <small class="text-success fw-bold text-uppercase" style="font-size: 10px;">PROYECTO FINAL</small>
                <h1 class="fw-bold h2 m-0">Gestión de Imágenes</h1>
            </div>
            <a href="visor.php" class="btn-abrir-visor">Abrir visor</a>
        </div>

        <div class="row g-4">
            <div class="col-md-5">
                <div class="card-custom">
                    <p class="text-uppercase small fw-bold text-muted mb-4"><i class="bi bi-cloud-upload me-2"></i>Nueva Carga</p>
                    
                    <form action="subir.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label-custom mb-2">NOMBRE DE LA IMAGEN</label>
                            <input type="text" name="nombre_personalizado" class="form-control bg-dark text-white border-secondary" placeholder="Ej: Foto de Servidor" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label-custom mb-2">SELECCIONAR ARCHIVO</label>
                            <input type="file" name="foto" class="form-control bg-dark text-white border-secondary" accept="image/*" required>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                            SUBIR AL SERVIDOR
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card-custom">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <p class="text-uppercase small fw-bold text-muted mb-0">Galería del Servidor</p>
                        <span class="badge bg-dark border border-secondary"><?php echo count($imagenes); ?> fotos</span>
                    </div>
                    
                    <div class="overflow-auto" style="max-height: 480px;">
                        <?php if (count($imagenes) > 0): ?>
                            <?php foreach ($imagenes as $img): ?>
                            <div class="img-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo $img['ruta']; ?>" class="rounded me-3" style="width:45px; height:45px; object-fit:cover; border: 1px solid #30363d;">
                                    <div>
                                        <div class="fw-bold text-white small"><?php echo htmlspecialchars($img['nombre']); ?></div>
                                        <small class="text-muted" style="font-size: 11px;">ID: <?php echo $img['id']; ?></small>
                                    </div>
                                </div>
                                <a href="visor.php" class="text-success"><i class="bi bi-arrow-right-circle fs-5"></i></a>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-muted py-4 small">No hay imágenes disponibles.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

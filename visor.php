<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// 1. CONEXIONES A BASES DE DATOS
include 'conexion.php';    // MariaDB
include 'conexion_pg.php'; // PostgreSQL

// 2. LÓGICA DINÁMICA POR ID (Lo que pidió el profe)
$id_solicitado = isset($_GET['id']) ? intval($_GET['id']) : null;

try {
    if ($id_solicitado) {
        $stmt = $pdo->prepare("SELECT * FROM imagenes WHERE id = ?");
        $stmt->execute([$id_solicitado]);
        $imagen_actual = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->query("SELECT * FROM imagenes ORDER BY id DESC LIMIT 1");
        $imagen_actual = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    $prev_id = null;
    $next_id = null;

    if ($imagen_actual) {
        $id_curr = $imagen_actual['id'];
        $prev_id = $pdo->query("SELECT id FROM imagenes WHERE id < $id_curr ORDER BY id DESC LIMIT 1")->fetchColumn();
        $next_id = $pdo->query("SELECT id FROM imagenes WHERE id > $id_curr ORDER BY id ASC LIMIT 1")->fetchColumn();
    }

    if (isset($pdo_pg) && $imagen_actual) {
        $stmt_pg = $pdo_pg->prepare("INSERT INTO historial_visitas (usuario, accion) VALUES (?, ?)");
        $stmt_pg->execute([$_SESSION['usuario'], "Visualizó imagen ID: " . $imagen_actual['id']]);
    }

} catch (PDOException $e) {
    die("Error en DB: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visor Dinámico | TESVG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #0d1117; color: white; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .sidebar { background: #0d1117; border-right: 1px solid #30363d; height: 100vh; position: fixed; width: 250px; z-index: 100; }
        .main-content { margin-left: 250px; padding: 40px; }
        .visor-container { background: #161b22; border: 1px solid #30363d; border-radius: 15px; padding: 50px; position: relative; text-align: center; }
        #img-display { max-width: 100%; max-height: 480px; border-radius: 10px; border: 1px solid #30363d; }
        .btn-arrow { background: none; border: none; color: #2ea043; font-size: 3.5rem; position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; text-decoration: none; }
        .btn-arrow:hover { color: #3fb950; scale: 1.1; transition: 0.2s; }
        .prev { left: 20px; }
        .next { right: 20px; }
        .nav-link { color: #8b949e; margin-bottom: 20px; display: block; text-decoration: none; }
        .nav-link.active { color: #2ea043; font-weight: bold; }
        .modulo-card { background: #161b22; border: 1px solid #30363d; border-radius: 12px; padding: 25px; transition: 0.3s; height: 100%; }
        .modulo-card:hover { border-color: #2ea043; }
        .info-img { color: #8b949e; margin-top: 15px; font-size: 1rem; }
    </style>
</head>
<body>

    <div class="sidebar p-4">
        <h3 class="text-success fw-bold">TESVG</h3>
        <p class="text-muted small">ISC - 8vo Semestre</p>
        <hr class="border-secondary">
        <nav class="mt-5">
            <a href="index.php" class="nav-link"><i class="bi bi-upload"></i> Subir Imagen</a>
            <a href="visor.php" class="nav-link active"><i class="bi bi-eye"></i> Visor Dinámico</a>
        </nav>
        <div style="position: absolute; bottom: 40px; width: 80%;">
            <a href="logout.php" class="btn btn-outline-danger w-100 btn-sm">Cerrar sesión</a>
        </div>
    </div>

    <div class="main-content">
        <header class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <span class="text-success small fw-bold">MONITOR DE SERVIDORES</span>
                <h1 class="fw-bold m-0">Visor de Imágenes</h1>
            </div>
            <a href="index.php" class="btn btn-dark border-secondary px-4"><i class="bi bi-plus-lg"></i> Gestionar</a>
        </header>

        <div class="visor-container mb-4">
            <?php if ($imagen_actual): ?>
                <?php if ($prev_id): ?>
                    <a href="visor.php?id=<?php echo $prev_id; ?>" class="btn-arrow prev">&#10094;</a>
                <?php endif; ?>

                <img id="img-display" src="<?php echo $imagen_actual['ruta']; ?>" alt="Cargando...">

                <?php if ($next_id): ?>
                    <a href="visor.php?id=<?php echo $next_id; ?>" class="btn-arrow next">&#10095;</a>
                <?php endif; ?>

                <div class="info-img">
                    <h4 class="text-white fw-bold mb-1"><?php echo htmlspecialchars($imagen_actual['nombre']); ?></h4>
                    <span class="text-success fw-bold">ID: <?php echo $imagen_actual['id']; ?></span>
                </div>
            <?php else: ?>
                <div class="py-5">
                    <i class="bi bi-images text-muted display-1"></i>
                    <p class="text-muted mt-3">Sube imágenes para activar el visor.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mb-5 d-flex justify-content-center gap-3">
            <?php if ($imagen_actual): ?>
                <button class="btn btn-warning px-4 fw-bold" onclick="editar(<?php echo $imagen_actual['id']; ?>, '<?php echo $imagen_actual['nombre']; ?>')">
                    <i class="bi bi-pencil-square"></i> Editar Nombre
                </button>
                <form action="eliminar.php" method="POST" onsubmit="return confirm('¿Eliminar permanentemente?')">
                    <input type="hidden" name="id" value="<?php echo $imagen_actual['id']; ?>">
                    <button type="submit" class="btn btn-danger px-4 fw-bold">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <h5 class="text-success mb-4 mt-5">MÓDULOS DE PRÁCTICA</h5>
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="modulo-card text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/2906/2906274.png" width="55" class="mb-3">
                    <h6 class="fw-bold text-white">Repositorio</h6>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <button class="btn btn-dark btn-sm border-secondary text-success px-3">Ver</button>
                        <button class="btn btn-dark btn-sm border-secondary px-3">Código</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="modulo-card text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/1055/1055685.png" width="55" class="mb-3">
                    <h6 class="fw-bold text-white">Diseño Ajustable</h6>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <button class="btn btn-dark btn-sm border-secondary text-success px-3">Ver</button>
                        <button class="btn btn-dark btn-sm border-secondary px-3">Código</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="modulo-card text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/2163/2163351.png" width="55" class="mb-3">
                    <h6 class="fw-bold text-white">Integridad</h6>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <button class="btn btn-dark btn-sm border-secondary text-success px-3">Ver</button>
                        <button class="btn btn-dark btn-sm border-secondary px-3">Código</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="modulo-card text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/1157/1157109.png" width="55" class="mb-3">
                    <h6 class="fw-bold text-white">Seguridad</h6>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <button class="btn btn-dark btn-sm border-secondary text-success px-3">Ver</button>
                        <button class="btn btn-dark btn-sm border-secondary px-3">Código</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function editar(id, nombreActual) {
            const nuevoNombre = prompt("Nuevo nombre para la imagen:", nombreActual);
            if (nuevoNombre && nuevoNombre.trim() !== "" && nuevoNombre !== nombreActual) {
                window.location.href = `editar.php?id=${id}&nombre=${encodeURIComponent(nuevoNombre)}`;
            }
        }
    </script>
</body>
</html>

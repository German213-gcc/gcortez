<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// 1. CONEXIONES A BASES DE DATOS (Doble Backend para Rúbrica)
include 'conexion.php';    // MariaDB
include 'conexion_pg.php'; // PostgreSQL

// 2. REGISTRO DE ACTIVIDAD EN POSTGRESQL
if (isset($pdo_pg)) {
    try {
        $stmt_pg = $pdo_pg->prepare("INSERT INTO historial_visitas (usuario) VALUES (?)");
        $stmt_pg->execute([$_SESSION['usuario']]);
    } catch (Exception $e) {
        // Fallo silencioso para no interrumpir la experiencia
    }
}

// 3. CONSULTA DE IMÁGENES (MariaDB)
try {
    $query = $pdo->query("SELECT * FROM imagenes ORDER BY id DESC");
    $imagenes = $query->fetchAll(PDO::FETCH_ASSOC);
    $json_imagenes = json_encode($imagenes);
} catch (PDOException $e) {
    die("Error en DB: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visor de Imágenes | TESVG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #0d1117; color: white; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .sidebar { background: #0d1117; border-right: 1px solid #30363d; height: 100vh; position: fixed; width: 250px; z-index: 100; }
        .main-content { margin-left: 250px; padding: 40px; }
        
        /* Visor con Transiciones Fluidas (Rúbrica UX) */
        .visor-container { background: #161b22; border: 1px solid #30363d; border-radius: 15px; padding: 50px; position: relative; text-align: center; }
        #img-display { 
            max-width: 100%; 
            max-height: 480px; 
            border-radius: 10px; 
            border: 1px solid #30363d; 
            transition: opacity 0.4s ease-in-out, transform 0.4s ease; /* Transición fluida */
        }
        .fade-out { opacity: 0; transform: scale(0.98); }

        .btn-arrow { background: none; border: none; color: #2ea043; font-size: 3.5rem; position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; }
        .btn-arrow:hover { color: #3fb950; scale: 1.1; transition: 0.2s; }
        .prev { left: 20px; }
        .next { right: 20px; }
        
        .modulo-card { background: #161b22; border: 1px solid #30363d; border-radius: 12px; padding: 25px; transition: 0.3s; }
        .modulo-card:hover { border-color: #2ea043; }
        .nav-link { color: #8b949e; margin-bottom: 20px; display: block; text-decoration: none; }
        .nav-link.active { color: #2ea043; font-weight: bold; }
        .info-img { color: #8b949e; margin-top: 15px; font-size: 0.9rem; }
    </style>
</head>
<body>

    <div class="sidebar p-4">
        <h3 class="text-success fw-bold">TESVG</h3>
        <p class="text-muted small">ING. SISTEMAS COMPUTACIONALES</p>
        <hr class="border-secondary">
        <nav class="mt-5">
            <a href="index.php" class="nav-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
            <a href="visor.php" class="nav-link active"><i class="bi bi-eye"></i> Visor de Imágenes</a>
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
            <?php if (count($imagenes) > 0): ?>
                <button class="btn-arrow prev" onclick="mover(-1)">&#10094;</button>
                <img id="img-display" src="<?php echo $imagenes[0]['ruta']; ?>" alt="Cargando...">
                <button class="btn-arrow next" onclick="mover(1)">&#10095;</button>
                <div class="info-img">
                    <span id="nombre-display" class="fw-bold text-white"><?php echo htmlspecialchars($imagenes[0]['nombre']); ?></span><br>
                    Imagen <span id="actual">1</span> de <?php echo count($imagenes); ?>
                </div>
            <?php else: ?>
                <div class="py-5">
                    <i class="bi bi-images text-muted display-1"></i>
                    <p class="text-muted mt-3">Sube imágenes para activar el slider.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mb-5 d-flex justify-content-center gap-3">
            <button class="btn btn-warning px-4 fw-bold" onclick="editarNombre()">
                <i class="bi bi-pencil-square"></i> Editar Nombre
            </button>

            <form action="eliminar.php" method="POST" id="form-eliminar">
                <input type="hidden" name="id" id="input-id-eliminar" value="<?php echo $imagenes[0]['id'] ?? ''; ?>">
                <button type="submit" class="btn btn-danger px-4 fw-bold" onclick="return confirm('¿Eliminar permanentemente?')">
                    <i class="bi bi-trash"></i> Eliminar
                </button>
            </form>
        </div>

        <h5 class="text-success mb-4 mt-5">MÓDULOS DE PRÁCTICA</h5>
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="modulo-card text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/2906/2906274.png" width="55" class="mb-3">
                    <h6 class="fw-bold">Repositorio</h6>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <button class="btn btn-dark btn-sm border-secondary text-success px-3">Ver</button>
                        <button class="btn btn-dark btn-sm border-secondary px-3">Código</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="modulo-card text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/1055/1055685.png" width="55" class="mb-3">
                    <h6 class="fw-bold">Diseño Ajustable</h6>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <button class="btn btn-dark btn-sm border-secondary text-success px-3">Ver</button>
                        <button class="btn btn-dark btn-sm border-secondary px-3">Código</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="modulo-card text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/2163/2163351.png" width="55" class="mb-3">
                    <h6 class="fw-bold">Integridad</h6>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <button class="btn btn-dark btn-sm border-secondary text-success px-3">Ver</button>
                        <button class="btn btn-dark btn-sm border-secondary px-3">Código</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="modulo-card text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/1157/1157109.png" width="55" class="mb-3">
                    <h6 class="fw-bold">Seguridad</h6>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <button class="btn btn-dark btn-sm border-secondary text-success px-3">Ver</button>
                        <button class="btn btn-dark btn-sm border-secondary px-3">Código</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const fotos = <?php echo $json_imagenes; ?>;
        let currentIdx = 0;

        function mover(sentido) {
            if (fotos.length === 0) return;
            
            const img = document.getElementById('img-display');
            img.classList.add('fade-out'); // Inicia desvanecimiento

            setTimeout(() => {
                currentIdx += sentido;
                if (currentIdx < 0) currentIdx = fotos.length - 1;
                if (currentIdx >= fotos.length) currentIdx = 0;

                img.src = fotos[currentIdx].ruta;
                document.getElementById('nombre-display').innerText = fotos[currentIdx].nombre;
                document.getElementById('actual').innerText = currentIdx + 1;
                document.getElementById('input-id-eliminar').value = fotos[currentIdx].id;
                
                img.classList.remove('fade-out'); // Termina desvanecimiento
            }, 400);
        }

        function editarNombre() {
            if (fotos.length === 0) return;
            const idActual = document.getElementById('input-id-eliminar').value;
            const nombreViejo = fotos[currentIdx].nombre;
            const nuevoNombre = prompt("Nuevo nombre para la imagen:", nombreViejo);
            
            if (nuevoNombre && nuevoNombre.trim() !== "" && nuevoNombre !== nombreViejo) {
                window.location.href = `editar.php?id=${idActual}&nombre=${encodeURIComponent(nuevoNombre)}`;
            }
        }
    </script>
</body>
</html>

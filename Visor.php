<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visor de Imágenes | TESVG 2026</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-wrapper">

    <!-- ===== SIDEBAR ===== -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <a href="index.php" class="sidebar-logo">TESVG</a>
            <span class="sidebar-sub">Panel de Control · 2026</span>
        </div>
        <ul class="nav flex-column p-2 mt-1">
            <li class="nav-section">Navegación</li>
            <li class="nav-item">
                <a href="index.php" class="nav-link">
                    <i class="bi bi-house-door nav-icon"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="visor.php" class="nav-link active">
                    <i class="bi bi-display nav-icon"></i> Visor
                </a>
            </li>
            <li class="nav-item mt-4">
                <a href="logout.php" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-left nav-icon"></i> Cerrar sesión
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <span class="sidebar-sub">TESVG &copy; 2026</span>
        </div>
    </nav>

    <!-- ===== CONTENIDO ===== -->
    <div id="content-area">

        <!-- Header -->
        <div class="page-header" id="top">
            <div>
                <p class="page-eyebrow">Monitor de Servidores</p>
                <h1 class="page-title">Visor de Imágenes</h1>
            </div>
            <a href="index.php" class="btn-visor-main">
                <i class="bi bi-cloud-arrow-up"></i> Gestionar imágenes
            </a>
        </div>

        <!-- ===== SLIDER ===== -->
        <section class="slider-section">

            <div class="monitor-container">
                <button class="arrow-btn" onclick="changeImage(-1)" aria-label="Anterior">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="slider-window">
                    <!-- AJAX inyecta el <img> aquí -->
                    <div id="contenido-imagen">
                        <div class="slide slide-placeholder">
                            <i class="bi bi-image"></i>
                            <p>Cargando imágenes...</p>
                        </div>
                    </div>
                </div>

                <button class="arrow-btn" onclick="changeImage(1)" aria-label="Siguiente">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>

            <!-- Nombre + botón borrar en la misma línea -->
            <div class="slider-meta">
                <span class="slider-dot"></span>
                <span id="img-name" class="slider-name">—</span>
                <span id="img-counter" class="slider-counter">— / —</span>
                <button class="btn-delete-visor" id="btn-borrar"
                        onclick="eliminarImagenActual()" title="Eliminar imagen">
                    <i class="bi bi-trash3"></i>
                    Eliminar
                </button>
            </div>

            <!-- Badge AJAX status -->
            <div id="ajax-status" class="ajax-badge" style="display:none;"></div>

            <!-- Confirmación de borrado -->
            <div id="delete-confirm" class="delete-confirm-box" style="display:none;">
                <div class="delete-confirm-inner">
                    <i class="bi bi-exclamation-triangle delete-warn-icon"></i>
                    <div class="delete-confirm-info">
                        <p class="delete-confirm-title">¿Eliminar esta imagen?</p>
                        <p class="delete-confirm-sub" id="delete-confirm-name"></p>
                    </div>
                    <div class="delete-confirm-btns">
                        <button class="btn-delete-cancel" onclick="cancelarBorrado()">
                            Cancelar
                        </button>
                        <button class="btn-delete-ok" id="btn-confirmar-borrar">
                            <i class="bi bi-trash3"></i> Sí, eliminar
                        </button>
                    </div>
                </div>
            </div>

        </section>

        <div class="section-divider"></div>

        <!-- ===== MÓDULOS ===== -->
        <section id="seccion-tarjetas" class="modules-section">
            <div class="modules-header">
                <div>
                    <p class="page-eyebrow">Prácticas</p>
                    <h2 class="modules-title">Módulos</h2>
                </div>
            </div>
            <div class="row g-4">
                <?php
                $proyectos = [
                    ["titulo" => "Repositorio",      "img" => "repo.jpg",  "badge" => "teal", "tag" => "Git"],
                    ["titulo" => "Diseño Ajustable", "img" => "lap.jpg",   "badge" => "gold", "tag" => "CSS"],
                    ["titulo" => "Listas",            "img" => "lista.jpg", "badge" => "teal", "tag" => "HTML"],
                    ["titulo" => "Links",             "img" => "link.jpg",  "badge" => "gold", "tag" => "Web"],
                ];
                foreach ($proyectos as $p) {
                    echo '
                    <div class="col-lg-3 col-md-6">
                        <div class="card module-card">
                            <div class="card-img-wrap">
                                <img src="img/'.$p['img'].'" alt="'.$p['titulo'].'">
                            </div>
                            <div class="card-body">
                                <div class="card-top">
                                    <h6 class="card-title">'.$p['titulo'].'</h6>
                                    <span class="badge-'.$p['badge'].'">'.$p['tag'].'</span>
                                </div>
                                <div class="card-actions">
                                    <button class="btn-card btn-view"
                                            onclick="document.getElementById(\'top\').scrollIntoView({behavior:\'smooth\'})">
                                        <i class="bi bi-eye"></i> Ver
                                    </button>
                                    <button class="btn-card btn-code" onclick="verCodigo(\''.$p['titulo'].'\')">
                                        <i class="bi bi-code-slash"></i> Código
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </section>

    </div><!-- /content-area -->
</div><!-- /main-wrapper -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
let current = 0;
let fotos   = [];

/* Lee ?idx= de la URL si viene desde la lista */
(function() {
    let params = new URLSearchParams(window.location.search);
    let idx    = parseInt(params.get('idx'));
    if (!isNaN(idx) && idx >= 0) current = idx;
})();

/* ---- Carga inicial ---- */
function cargarFotos() {
    $.ajax({
        url: 'obtener_todas.php',
        type: 'GET',
        success: function(res) {
            try { fotos = JSON.parse(res); } catch(e) { return; }
            if (fotos.length > 0) {
                if (current >= fotos.length) current = 0;
                actualizarVista();
            } else {
                $('#contenido-imagen').html(
                    '<div class="slide slide-placeholder">' +
                    '<i class="bi bi-inbox"></i><p>No hay imágenes</p>' +
                    '</div>'
                );
            }
        }
    });
}

/* ---- changeImage — estructura AJAX del maestro ---- */
function changeImage(dir) {
    if (fotos.length === 0) return;

    let sig        = (current + dir + fotos.length) % fotos.length;
    let nombreProx = fotos[sig].nombre;

    $.ajax({
        url: 'obtener_todas.php',
        cache: false,
        success: function() {
            current = sig;
            actualizarVista();

            $('#ajax-status')
                .stop(true)
                .text('AJAX éxito: Mostrando -> ' + nombreProx)
                .fadeIn(200).delay(1500).fadeOut(400);
        }
    });
}

/* ---- Actualiza imagen y meta ---- */
function actualizarVista() {
    let foto = fotos[current];
    /* Inyecta <img> directo — igual que el ejemplo del maestro */
    $('#contenido-imagen').html(
        '<div class="slide"><img src="' + foto.ruta +
        '" alt="' + foto.nombre + '"></div>'
    );
    $('#img-name').text(foto.nombre);
    $('#img-counter').text((current + 1) + ' / ' + fotos.length);
}

/* ---- Eliminar imagen ---- */
function eliminarImagenActual() {
    if (fotos.length === 0) return;

    document.getElementById('delete-confirm-name').textContent =
        '"' + fotos[current].nombre + '"';
    document.getElementById('delete-confirm').style.display = 'block';
    document.getElementById('btn-borrar').style.display     = 'none';

    document.getElementById('btn-confirmar-borrar').onclick = function() {
        confirmarBorrado(fotos[current].id, fotos[current].ruta, fotos[current].nombre);
    };
}

function cancelarBorrado() {
    document.getElementById('delete-confirm').style.display = 'none';
    document.getElementById('btn-borrar').style.display     = 'inline-flex';
}

function confirmarBorrado(id, ruta, nombre) {
    let btnOk = document.getElementById('btn-confirmar-borrar');
    btnOk.disabled = true;
    btnOk.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Eliminando...';

    $.ajax({
        url: 'eliminar_foto.php',
        type: 'POST',
        data: { id: id, ruta: ruta },
        success: function(res) {
            if (res.trim() === 'success') {
                fotos = fotos.filter(function(f) { return f.id != id; });
                document.getElementById('delete-confirm').style.display = 'none';

                if (fotos.length === 0) {
                    $('#contenido-imagen').html(
                        '<div class="slide slide-placeholder">' +
                        '<i class="bi bi-inbox"></i><p>No hay imágenes</p>' +
                        '</div>'
                    );
                    $('#img-name').text('—');
                    $('#img-counter').text('0 / 0');
                    document.getElementById('btn-borrar').style.display = 'none';
                } else {
                    if (current >= fotos.length) current = fotos.length - 1;
                    actualizarVista();
                    document.getElementById('btn-borrar').style.display = 'inline-flex';
                }

                $('#ajax-status')
                    .stop(true)
                    .text('"' + nombre + '" eliminada')
                    .fadeIn(200).delay(1800).fadeOut(400);
            } else {
                alert('Error al eliminar: ' + res);
                btnOk.disabled = false;
                btnOk.innerHTML = '<i class="bi bi-trash3"></i> Sí, eliminar';
            }
        },
        error: function() {
            alert('Error de conexión al intentar eliminar.');
            btnOk.disabled = false;
            btnOk.innerHTML = '<i class="bi bi-trash3"></i> Sí, eliminar';
        }
    });
}

function verCodigo(titulo) {
    alert('Mostrando código fuente de: ' + titulo);
}

$(document).ready(cargarFotos);
</script>
</body>
</html>
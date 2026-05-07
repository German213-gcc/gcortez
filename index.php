<?php
// 1. Bloque de seguridad: Siempre al puro inicio
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
    <title>Gestión de Imágenes | TESVG 2026</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-wrapper">

    <!-- ===== SIDEBAR CORREGIDO ===== -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <a href="index.php" class="sidebar-logo">TESVG</a>
            <span class="sidebar-sub">Panel de Control · 2026</span>
        </div>
        <ul class="nav flex-column p-2 mt-1">
            <li class="nav-section">Navegación</li>
            <li class="nav-item">
                <a href="index.php" class="nav-link active">
                    <i class="bi bi-house-door nav-icon"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="visor.php" target="_blank" class="nav-link">
                    <i class="bi bi-display nav-icon"></i> Visor
                </a>
            </li>
            <!-- NUEVO: Botón de Cerrar Sesión -->
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

    <!-- ===== CONTENIDO PRINCIPAL ===== -->
    <div id="content-area">

        <!-- PAGE HEADER -->
        <div class="page-header" id="top">
            <div>
                <p class="page-eyebrow">Monitor de Servidores</p>
                <h1 class="page-title">Gestión de Imágenes</h1>
            </div>
            <a href="visor.php" target="_blank" class="btn-visor-main">
                <i class="bi bi-play-circle"></i> Abrir visor
            </a>
        </div>

        <!-- PANEL: dos columnas -->
        <div class="panel-grid">

            <!-- COLUMNA IZQUIERDA: Subir -->
            <div class="panel-upload">
                <div class="panel-section-title">
                    <i class="bi bi-upload"></i> Subir imagen
                </div>

                <form id="formSubir">
                    <div class="form-group mb-3">
                        <label class="form-label-custom">Nombre</label>
                        <input type="text" name="nombre" id="inputNombre" class="input-custom"
                               placeholder="ej. Servidor Principal" required>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label-custom">Archivo</label>
                        <div class="file-drop-zone" id="dropZone">
                            <div class="file-drop-icon" id="drop-icon">
                                <i class="bi bi-image" id="drop-bi-icon"></i>
                            </div>
                            <p class="file-drop-title" id="file-title">Selecciona o arrastra una imagen</p>
                            <p class="file-drop-hint"  id="file-hint">PNG, JPG, WEBP — máx. 5 MB</p>
                        </div>
                        <input type="file" id="fotoInput" name="foto" accept="image/*" required style="display:none;">
                    </div>
                    <button type="button" id="btn-subir" class="btn-submit-main">
                        <i class="bi bi-cloud-arrow-up" id="btn-subir-icon"></i>
                        <span id="btn-subir-text">Subir al servidor</span>
                    </button>
                </form>

                <!-- Estado: éxito -->
                <div id="upload-success" style="display:none;">
                    <div class="estado-panel">
                        <div class="estado-icon estado-ok"><i class="bi bi-check-lg"></i></div>
                        <p class="estado-title">¡Imagen guardada!</p>
                        <p class="estado-sub" id="estado-nombre"></p>
                        <button type="button" class="btn-submit-main mt-3" id="btn-otra">
                            <i class="bi bi-plus-lg"></i> Subir otra
                        </button>
                    </div>
                </div>

                <!-- Estado: error -->
                <div id="upload-error" style="display:none;">
                    <div class="estado-panel">
                        <div class="estado-icon estado-err"><i class="bi bi-x-lg"></i></div>
                        <p class="estado-title">Algo salió mal</p>
                        <p class="estado-sub" id="estado-error-msg"></p>
                        <button type="button" class="btn-outline-panel mt-3" id="btn-reintentar">Reintentar</button>
                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA: Lista -->
            <div class="panel-list">
                <div class="panel-list-header">
                    <div class="panel-section-title">
                        <i class="bi bi-images"></i> Imágenes subidas
                    </div>
                    <span id="list-count" class="list-count-badge">0</span>
                </div>

                <div id="lista-imagenes" class="img-list">
                    <div class="img-list-empty">
                        <i class="bi bi-inbox"></i>
                        <p>Sin imágenes aún</p>
                    </div>
                </div>
            </div>

        </div><!-- /panel-grid -->

    </div><!-- /content-area -->
</div><!-- /main-wrapper -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* =============================================
   CARGA DE LISTA
   ============================================= */
function cargarLista() {
    $.ajax({
        url: 'obtener_todas.php',
        type: 'GET',
        success: function(res) {
            let imgs;
            try { imgs = JSON.parse(res); } catch(e) { return; }

            let lista = $('#lista-imagenes');
            lista.empty();
            $('#list-count').text(imgs.length);

            if (imgs.length === 0) {
                lista.html(
                    '<div class="img-list-empty">' +
                    '<i class="bi bi-inbox"></i><p>Sin imágenes aún</p>' +
                    '</div>'
                );
                return;
            }

            imgs.forEach(function(img, i) {
                let ext = img.ruta ? img.ruta.split('.').pop().toUpperCase() : '—';
                lista.append(
                    '<div class="img-list-row" onclick="window.open(\'visor.php?idx=' + i + '\', \'_blank\')">' +
                    '<div class="img-list-thumb">' +
                    '<img src="' + img.ruta + '" alt="' + img.nombre + '">' +
                    '</div>' +
                    '<div class="img-list-info">' +
                    '<span class="img-list-name">' + img.nombre + '</span>' +
                    '<span class="img-list-meta">ID:&nbsp;' + (img.id || (i + 1)) +
                    '&nbsp;·&nbsp;' + ext + '</span>' +
                    '</div>' +
                    '<i class="bi bi-arrow-up-right-circle img-list-arrow"></i>' +
                    '</div>'
                );
            });
        }
    });
}

/* =============================================
   DROP ZONE
   ============================================= */
const dropZone  = document.getElementById('dropZone');
const fotoInput = document.getElementById('fotoInput');

dropZone.addEventListener('click', function() { fotoInput.click(); });

fotoInput.addEventListener('change', function() {
    if (this.files && this.files[0]) mostrarPreview(this.files[0]);
});

dropZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('drag-over');
});

dropZone.addEventListener('dragleave', function(e) {
    if (!this.contains(e.relatedTarget)) this.classList.remove('drag-over');
});

dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('drag-over');
    let file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        let dt = new DataTransfer();
        dt.items.add(file);
        fotoInput.files = dt.files;
        mostrarPreview(file);
    }
});

function mostrarPreview(file) {
    let reader = new FileReader();
    reader.onload = function(e) {
        dropZone.style.backgroundImage    = 'url(' + e.target.result + ')';
        dropZone.style.backgroundSize     = 'cover';
        dropZone.style.backgroundPosition = 'center';
        dropZone.classList.add('has-file');
    };
    reader.readAsDataURL(file);
    document.getElementById('file-title').textContent = file.name;
    document.getElementById('drop-bi-icon').className = 'bi bi-check-lg';
    document.getElementById('drop-bi-icon').style.color   = '#63c0a8';
}

/* =============================================
   SUBIR CON AJAX (Sincronizado con subir.php)
   ============================================= */
document.getElementById('btn-subir').addEventListener('click', function() {
    let nombre  = document.getElementById('inputNombre').value.trim();
    let archivo = fotoInput.files[0];

    if (!nombre)  { document.getElementById('inputNombre').focus(); return; }
    if (!archivo) { return; }

    let btnSubir = document.getElementById('btn-subir');
    btnSubir.disabled = true;
    document.getElementById('btn-subir-text').textContent = 'Subiendo...';

    let formData = new FormData();
    formData.append('nombre', nombre);
    formData.append('foto',   archivo); // Se envía como 'foto'

    $.ajax({
        url: 'subir.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            // El servidor responde 'success' si todo salió bien
            document.getElementById('formSubir').style.display      = 'none';
            document.getElementById('upload-success').style.display = 'block';
            document.getElementById('estado-nombre').textContent    = '"' + nombre + '" añadida';
            cargarLista();
        },
        error: function(xhr) {
            document.getElementById('formSubir').style.display      = 'none';
            document.getElementById('upload-error').style.display   = 'block';
            document.getElementById('estado-error-msg').textContent = 'Error al subir imagen.';
        }
    });
});

document.getElementById('btn-otra').addEventListener('click', resetForm);

function resetForm() {
    document.getElementById('formSubir').style.display      = 'block';
    document.getElementById('upload-success').style.display = 'none';
    document.getElementById('inputNombre').value = '';
    fotoInput.value  = '';
    dropZone.style.backgroundImage = '';
    dropZone.classList.remove('has-file');
    document.getElementById('file-title').textContent = 'Selecciona o arrastra una imagen';
    document.getElementById('btn-subir').disabled = false;
    document.getElementById('btn-subir-text').textContent = 'Subir al servidor';
}

$(document).ready(cargarLista);
</script>
</body>
</html>
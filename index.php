<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control | TESVG 2026</title>
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
            <a href="#top" class="sidebar-logo">TESVG</a>
            <span class="sidebar-sub">Panel de Control · 2026</span>
        </div>

        <ul class="nav flex-column p-2 mt-1">
            <li class="nav-section">Navegación</li>

            <li class="nav-item">
                <a href="#top" class="nav-link active" onclick="irAlVisor()">
                    <i class="bi bi-house-door nav-icon"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="#seccion-tarjetas" class="nav-link">
                    <i class="bi bi-grid-1x2 nav-icon"></i>
                    Módulos
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="bi bi-cloud-arrow-up nav-icon"></i>
                    Subir Imagen
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <span class="sidebar-sub">TESVG &copy; 2026</span>
        </div>
    </nav>

    <!-- ===== CONTENIDO PRINCIPAL ===== -->
    <div id="content-area">

        <!-- Encabezado de página -->
        <div class="page-header" id="top">
            <div>
                <p class="page-eyebrow">Monitor de Servidores</p>
                <h1 class="page-title">Panel de Control</h1>
            </div>
            <button class="btn-upload" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="bi bi-plus-lg"></i>
                Nueva imagen
            </button>
        </div>

        <!-- ===== SLIDER ===== -->
        <section class="slider-section">

            <div class="monitor-container">
                <button class="arrow-btn" onclick="changeImage(-1)" aria-label="Anterior">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="slider-window">
                    <div class="slider-track" id="slider-track">
                        <div class="slide">
                            <img src="img/placeholder.jpg" alt="Imagen del servidor">
                        </div>
                    </div>
                </div>

                <button class="arrow-btn" onclick="changeImage(1)" aria-label="Siguiente">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>

            <!-- Info de imagen activa -->
            <div class="slider-meta">
                <span class="slider-dot"></span>
                <span id="img-name" class="slider-name">Monitor Activo</span>
                <span id="img-counter" class="slider-counter">1 / 1</span>
            </div>

            <!-- Status AJAX -->
            <div id="ajax-status" class="ajax-badge" style="display:none;"></div>

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
                                <img src="img/'.$p['img'].'" class="card-img-top" alt="'.$p['titulo'].'">
                            </div>
                            <div class="card-body">
                                <div class="card-top">
                                    <h6 class="card-title">'.$p['titulo'].'</h6>
                                    <span class="badge-'.$p['badge'].'">'.$p['tag'].'</span>
                                </div>
                                <div class="card-actions">
                                    <button class="btn-card btn-view" onclick="irAlVisor()">
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

    </div>
</div>

<!-- ===== MODAL: Subir Imagen ===== -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-label="Subir imagen" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-dark">
            <form id="formSubir">

                <div class="modal-header">
                    <div>
                        <div class="modal-title-wrap">
                            <div class="modal-header-icon">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </div>
                            <h5 class="modal-title-text">Subir imagen</h5>
                        </div>
                        <p class="modal-subtitle">Se añadirá al monitor de servidores</p>
                    </div>
                    <button type="button" class="modal-close-btn" data-bs-dismiss="modal" aria-label="Cerrar">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-4">
                        <label class="form-label-custom">Nombre</label>
                        <input type="text" name="nombre" class="input-custom" placeholder="ej. Servidor Principal" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label-custom">Archivo</label>
                        <div class="file-drop-zone" id="dropZone">
                            <div class="file-drop-icon" id="drop-icon">
                                <i class="bi bi-image" id="drop-bi-icon"></i>
                            </div>
                            <p class="file-drop-title" id="file-title">Selecciona o arrastra una imagen</p>
                            <p class="file-drop-hint" id="file-hint">PNG, JPG, WEBP — máx. 5 MB</p>
                        </div>
                        <input type="file" name="foto" id="fotoInput" accept="image/*" required style="display:none;">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-subir" class="btn-modal-submit">
                        <i class="bi bi-cloud-arrow-up" id="btn-subir-icon"></i>
                        <span id="btn-subir-text">Subir al servidor</span>
                    </button>
                </div>

            </form>

            <!-- Estado: éxito -->
            <div id="modal-success" style="display:none;">
                <div class="modal-estado">
                    <div class="estado-icon estado-ok">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <p class="estado-title">¡Imagen guardada!</p>
                    <p class="estado-sub" id="estado-nombre"></p>
                    <button type="button" class="btn-modal-submit mt-3" id="btn-otra">
                        <i class="bi bi-plus-lg"></i> Subir otra
                    </button>
                </div>
            </div>

            <!-- Estado: error -->
            <div id="modal-error" style="display:none;">
                <div class="modal-estado">
                    <div class="estado-icon estado-err">
                        <i class="bi bi-x-lg"></i>
                    </div>
                    <p class="estado-title">Algo salió mal</p>
                    <p class="estado-sub" id="estado-error-msg">Intenta de nuevo</p>
                    <button type="button" class="btn-modal-cancel mt-3" id="btn-reintentar">
                        Reintentar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== SCRIPTS ===== -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let current = 0;
    let fotos = [];

    /* Carga inicial de fotos */
    function cargarFotos() {
        $.ajax({
            url: 'obtener_todas.php',
            type: 'GET',
            success: function(res) {
                try { fotos = JSON.parse(res); } catch(e) { return; }

                if (fotos.length > 0) {
                    $('#slider-track').empty();
                    fotos.forEach(f => {
                        $('#slider-track').append(
                            `<div class="slide"><img src="${f.ruta}" alt="${f.nombre}"></div>`
                        );
                    });
                    actualizarMeta();
                }
            }
        });
    }

    /* Cambiar imagen — estructura AJAX según el maestro */
    function changeImage(dir) {
        if (fotos.length === 0) return;

        let sig = (current + dir + fotos.length) % fotos.length;
        let nombreProx = fotos[sig].nombre;

        $.ajax({
            url: 'obtener_todas.php',
            cache: false,
            success: function() {
                alert("AJAX éxito: Cambiando a -> " + nombreProx);

                current = sig;
                $('#slider-track').css('transform', `translateX(${-current * 100}%)`);
                actualizarMeta();

                $('#ajax-status')
                    .stop(true)
                    .text('Cargando → ' + nombreProx)
                    .fadeIn(150)
                    .delay(1400)
                    .fadeOut(300);
            }
        });
    }

    /* Actualiza nombre e índice debajo del slider */
    function actualizarMeta() {
        if (fotos.length === 0) return;
        $('#img-name').text(fotos[current].nombre);
        $('#img-counter').text((current + 1) + ' / ' + fotos.length);
    }

    function irAlVisor() {
        document.getElementById('top').scrollIntoView({ behavior: 'smooth' });
    }

    function verCodigo(titulo) {
        alert('Mostrando código fuente de: ' + titulo);
    }

    /* ===== DROP ZONE ===== */
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
        let size = file.size < 1024 * 1024
            ? (file.size / 1024).toFixed(0) + ' KB'
            : (file.size / (1024 * 1024)).toFixed(1) + ' MB';

        let reader = new FileReader();
        reader.onload = function(e) {
            dropZone.style.backgroundImage    = 'url(' + e.target.result + ')';
            dropZone.style.backgroundSize     = 'cover';
            dropZone.style.backgroundPosition = 'center';
            dropZone.style.borderStyle        = 'solid';
            dropZone.classList.add('has-file');
        };
        reader.readAsDataURL(file);

        document.getElementById('file-title').textContent = file.name;
        document.getElementById('file-hint').textContent  = size;
        document.getElementById('drop-icon').style.background = 'rgba(99,192,168,0.25)';
        document.getElementById('drop-bi-icon').className = 'bi bi-check-lg';
        document.getElementById('drop-bi-icon').style.color = '#63c0a8';
    }

    /* ===== SUBIR CON AJAX (sin navegar a subir.php) ===== */
    document.getElementById('btn-subir').addEventListener('click', function() {
        let nombre = document.querySelector('#formSubir [name="nombre"]').value.trim();
        let archivo = fotoInput.files[0];

        if (!nombre) {
            document.querySelector('#formSubir [name="nombre"]').focus();
            return;
        }
        if (!archivo) {
            dropZone.classList.add('drag-over');
            setTimeout(() => dropZone.classList.remove('drag-over'), 600);
            return;
        }

        // Estado: cargando
        let btnSubir = document.getElementById('btn-subir');
        btnSubir.disabled = true;
        document.getElementById('btn-subir-icon').className = 'bi bi-arrow-repeat spin';
        document.getElementById('btn-subir-text').textContent = 'Subiendo...';

        let formData = new FormData();
        formData.append('nombre', nombre);
        formData.append('foto', archivo);

        $.ajax({
            url: 'subir.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                // Mostrar pantalla de éxito dentro del modal
                document.getElementById('formSubir').style.display    = 'none';
                document.getElementById('modal-error').style.display  = 'none';
                document.getElementById('modal-success').style.display = 'block';
                document.getElementById('estado-nombre').textContent  = '"' + nombre + '" añadida al monitor';

                // Recargar slider en background
                cargarFotos();
            },
            error: function(xhr) {
                document.getElementById('formSubir').style.display   = 'none';
                document.getElementById('modal-success').style.display = 'none';
                document.getElementById('modal-error').style.display  = 'block';
                document.getElementById('estado-error-msg').textContent =
                    xhr.responseText || 'Error al conectar con el servidor';
            }
        });
    });

    // Botón "Subir otra" — resetea el modal
    document.getElementById('btn-otra').addEventListener('click', resetModal);
    document.getElementById('btn-reintentar').addEventListener('click', resetModal);

    function resetModal() {
        document.getElementById('formSubir').style.display     = 'block';
        document.getElementById('modal-success').style.display = 'none';
        document.getElementById('modal-error').style.display   = 'none';

        // Reset form
        document.querySelector('#formSubir [name="nombre"]').value = '';
        fotoInput.value = '';
        dropZone.style.backgroundImage = '';
        dropZone.style.backgroundSize  = '';
        dropZone.style.borderStyle     = '';
        dropZone.classList.remove('has-file', 'drag-over');
        document.getElementById('file-title').textContent   = 'Selecciona o arrastra una imagen';
        document.getElementById('file-hint').textContent    = 'PNG, JPG, WEBP — máx. 5 MB';
        document.getElementById('drop-icon').style.background = '';
        document.getElementById('drop-bi-icon').className  = 'bi bi-image';
        document.getElementById('drop-bi-icon').style.color = '';

        // Reset botón
        let btnSubir = document.getElementById('btn-subir');
        btnSubir.disabled = false;
        document.getElementById('btn-subir-icon').className = 'bi bi-cloud-arrow-up';
        document.getElementById('btn-subir-text').textContent = 'Subir al servidor';
    }

    // Reset completo al cerrar el modal
    document.getElementById('uploadModal').addEventListener('hidden.bs.modal', resetModal);

    $(document).ready(cargarFotos);
</script>
</body>
</html>
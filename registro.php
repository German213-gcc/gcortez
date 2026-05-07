<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta | TESVG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #1a1d20; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; font-family: 'Segoe UI', sans-serif; }
        .register-card { background: white; padding: 40px; border-radius: 20px; width: 100%; max-width: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-align: center; }
        .btn-primary { background-color: #0d6efd; border: none; padding: 12px; border-radius: 10px; font-weight: bold; }
        .input-group-text { background: none; border-right: none; color: #999; }
        .form-control { border-left: none; padding: 12px; }
    </style>
</head>
<body>
<div class="register-card">
    <h2 class="text-primary fw-bold">Crear cuenta</h2>
    <p class="text-muted">Regístrate para acceder al panel</p>
    
    <form action="procesar_registro.php" method="POST">
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" name="nombre" class="form-control" placeholder="Nombre completo" required>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="correo" class="form-control" placeholder="Correo electrónico" required>
        </div>
        <div class="input-group mb-4">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
    </form>
    <div class="mt-3">
        ¿Ya tienes cuenta? <a href="login.php" class="text-decoration-none fw-bold">Iniciar sesión</a>
    </div>
</div>
</body>
</html>
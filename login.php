<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | AdminPanel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #1a1d20; font-family: 'Segoe UI', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-card { background: white; padding: 40px; border-radius: 20px; width: 100%; max-width: 400px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); text-align: center; }
        .btn-primary { background-color: #0d6efd; border: none; padding: 12px; border-radius: 10px; font-weight: bold; }
    </style>
</head>
<body>

<div class="login-card">
    <h2>🔒 Iniciar sesión</h2>
    <p>Ingresa tus datos para continuar</p>

    <!-- CORRECCIÓN 1: El action debe ser validar_login.php -->
    <form action="validar_login.php" method="POST">
        <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <!-- CORRECCIÓN 2: Se agregó name="correo" -->
            <input type="email" name="correo" class="form-control" placeholder="Correo electrónico" required>
        </div>

        <div class="input-group mb-4">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <!-- CORRECCIÓN 3: Se agregó name="password" -->
            <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>

    <div class="footer-link mt-3">
        ¿No tienes cuenta? <br>
        <a href="registro.php">Crear cuenta</a>
    </div>
</div>

</body>
</html>

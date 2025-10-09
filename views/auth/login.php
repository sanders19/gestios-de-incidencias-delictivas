<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Sistema Policial Huancavelica</title>

    <!-- Bootstrap (opcional si lo usas) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tu archivo CSS -->
    <link rel="stylesheet" href="../../public/css/login.css">
</head>
<body>

    <div class="login-container">
        <img src="../../public/img/logo.png" alt="Logo" class="logo">
        <h2>Sistema Policial Huancavelica</h2>

        <form method="POST" action="#">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>

        <a href="#">¿Olvidaste tu contraseña?</a>
    </div>

</body>
</html>

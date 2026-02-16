<!DOCTYPE html>
<?php error_log('LOG VIEW: rendering login view'); ?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Sistema Policial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/login.css">
   
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-logo">
            <img src="/img/logo.png" alt="Logo PNP">
            <h2>Sistema Policial Huancavelica</h2>
            <p>Inicie sesión con sus credenciales</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="/login" class="login-form">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" name="nombre_usuario" class="form-control" placeholder="Ingrese su usuario" 
                    value="<?php echo htmlspecialchars($usuarioInput ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <div class="password-wrapper">
                    <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="Ingrese su contraseña" required>
                    <i class="bi bi-eye password-toggle" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Rol</label>
                <select name="rol" class="form-select" required>
                    <option value="">Seleccionar rol</option>
                    <option value="mesa" <?php echo ($rolSeleccionado ?? '')==='mesa'?'selected':''; ?>>Mesa de Partes</option>
                    <option value="seincri" <?php echo ($rolSeleccionado ?? '')==='seincri'?'selected':''; ?>>SEINCRI</option>
                    <option value="jefe" <?php echo ($rolSeleccionado ?? '')==='jefe'?'selected':''; ?>>Jefe</option>
                </select>
            </div>

            <button type="submit" class="btn btn-login w-100">Entrar</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('contrasena');
    const toggleIcon = document.getElementById('togglePassword');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
}
</script>

</body>
</html>

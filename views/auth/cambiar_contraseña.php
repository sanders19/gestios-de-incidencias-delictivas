<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Cambiar Contraseña (Primer Inicio)</h2>
<form method="POST" action="<?= BASE_URL ?>/cambiar-contrasena">
<form method="POST">
    <label>Nueva contraseña: <input type="password" name="nueva_contrasena" minlength="6" required></label><br><br>
    <label>Confirmar: <input type="password" name="confirmar_contrasena" minlength="6" required></label><br><br>
    <button type="submit">Actualizar Contraseña</button>
</form>
<a href="/logout">Cerrar sesión</a>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
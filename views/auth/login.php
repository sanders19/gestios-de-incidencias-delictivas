<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Iniciar Sesión</h2>
<form method="POST" action="/login">
    <label>Usuario: <input type="text" name="nombre_usuario" required></label><br><br>
    <label>Contraseña: <input type="password" name="contrasena" required></label><br><br>
    <label>Rol:
        <select name="rol" required>
            <option value="">Seleccionar rol</option>
            <option value="mesa">Mesa de Partes</option>
            <option value="seincri">SEINCRI</option>
            <option value="jefe">Jefe</option>
        </select>
    </label><br><br>
    <button type="submit">Entrar</button>
</form>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
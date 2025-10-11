<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Crear Nuevo Usuario</h2>
<form method="POST">
    <label>ID Usuario (ej. MESA-001): <input type="text" name="id_usuario" required></label><br>
    <label>Nombre de usuario (único): <input type="text" name="nombre_usuario" required></label><br>
    <label>Contraseña inicial: <input type="password" name="contrasena" minlength="6" required></label><br>
    <label>Rol:
        <select name="rol" required>
            <option value="mesa">Mesa de Partes</option>
            <option value="seincri">SEINCRI</option>
            <option value="jefe">Jefe</option>
        </select>
    </label><br>
    <label>Nombre completo: <input type="text" name="nombre_completo" required></label><br>
    <label>Comisaría: <input type="text" name="comisaria" value="Huancavelica Centro"></label><br>
    <button type="submit">Crear Usuario</button>
</form>
<a href="/jefe/dashboard">Volver</a>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
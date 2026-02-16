<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Editar Usuario - <?php echo htmlspecialchars($usuario['id_usuario']); ?></h4>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">ID Usuario</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['id_usuario']); ?>" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nombre de usuario</label>
                    <input type="text" name="nombre_usuario" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre_usuario']); ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="nombre_completo" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre_completo']); ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Rol</label>
                    <select name="rol" class="form-select" required>
                        <option value="mesa" <?php echo ($usuario['rol'] === 'mesa') ? 'selected' : ''; ?>>Mesa</option>
                        <option value="seincri" <?php echo ($usuario['rol'] === 'seincri') ? 'selected' : ''; ?>>SEINCRI</option>
                        <option value="jefe" <?php echo ($usuario['rol'] === 'jefe') ? 'selected' : ''; ?>>Jefe</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Comisaría</label>
                    <input type="text" name="comisaria" class="form-control" value="<?php echo htmlspecialchars($usuario['comisaria']); ?>">
                </div>

                <div class="col-12 text-end mt-3">
                    <a href="/jefe/usuarios" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<h2>Asignar Casos Pendientes</h2>
<?php if (empty($incidencias_pendientes)): ?>
    <p>No hay incidencias pendientes.</p>
<?php else: ?>
    <form method="POST">
        <label>Incidencia:
            <select name="id_incidencia" required>
                <?php foreach ($incidencias_pendientes as $inc): ?>
                    <option value="<?= htmlspecialchars($inc['id_incidencia']) ?>">
                        <?= htmlspecialchars($inc['id_incidencia']) ?> - <?= htmlspecialchars($inc['tipo_delito']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <label>Asignar a SEINCRI:
            <select name="asignado_a" required>
                <?php foreach ($seincri_usuarios as $u): ?>
                    <option value="<?= htmlspecialchars($u['id_usuario']) ?>">
                        <?= htmlspecialchars($u['nombre_completo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <button type="submit">Asignar Caso</button>
    </form>
<?php endif; ?>
<a href="/jefe/dashboard">Volver</a>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
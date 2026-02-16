<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="bi bi-clipboard-check"></i> Asignar Casos Pendientes</h4>
        </div>

        <div class="card-body">
            <?php if (empty($incidencias_pendientes)): ?>
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> No hay incidencias pendientes.
                </div>
            <?php else: ?>
                <form method="POST" class="p-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Incidencia</label>
                        <select name="id_incidencia" class="form-select" required>
                            <option value="">Seleccione una incidencia</option>
                            <?php foreach ($incidencias_pendientes as $inc): ?>
                                <option value="<?= htmlspecialchars($inc['id_incidencia']) ?>">
                                    <?= htmlspecialchars($inc['id_incidencia']) ?> - <?= htmlspecialchars($inc['tipo_delito']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Asignar a SEINCRI</label>
                        <select name="asignado_a" class="form-select" required>
                            <option value="">Seleccione un agente SEINCRI</option>
                            <?php foreach ($seincri_usuarios as $u): ?>
                                <option value="<?= htmlspecialchars($u['id_usuario']) ?>">
                                    <?= htmlspecialchars($u['nombre_completo']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send-check"></i> Asignar Caso
                        </button>
                    </div>
                </form>
            <?php endif; ?>

            <div class="text-end mt-3">
                <a href="/jefe/dashboard" class="btn btn-outline-success">
                    <i class="bi bi-arrow-left-circle"></i> Volver al Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

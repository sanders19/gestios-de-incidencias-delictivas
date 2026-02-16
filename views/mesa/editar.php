<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/layout.css">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success"><i class="bi bi-pencil-square"></i> Editar Incidencia</h2>
        <a href="<?= BASE_URL ?>/mesa/dashboard/<?= $incidencia['id_incidencia'] ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Cancelar
        </a>
    </div>

    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i> <strong>Nota:</strong> Solo puedes editar campos generales. Los datos de personas no se pueden modificar.
    </div>

    <form method="POST" class="card shadow-sm p-4 bg-light border-success">
        <h4 class="text-success">Detalles de la Incidencia</h4>
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Tipo de delito</label>
                <select name="tipo_delito" id="tipo-delito" class="form-select" required>
                    <option value="">Seleccionar tipo</option>
                    <?php foreach ($tiposUnicos as $tipo): ?>
                        <option value="<?= htmlspecialchars($tipo) ?>" 
                                <?= $incidencia['tipo_delito'] === $tipo ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tipo) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Clasificación</label>
                <select name="clasificacion_delito" id="clasificacion-delito" class="form-select" required>
                    <option value="">Seleccionar clasificación</option>
                    <?php foreach ($delitosClasificaciones as $dc): ?>
                        <option value="<?= htmlspecialchars($dc['clasificacion']) ?>"
                                data-tipo="<?= htmlspecialchars($dc['tipo_delito']) ?>"
                                <?= $incidencia['clasificacion_delito'] === $dc['clasificacion'] ? 'selected' : '' ?>
                                style="<?= $incidencia['tipo_delito'] === $dc['tipo_delito'] ? '' : 'display:none;' ?>">
                            <?= htmlspecialchars($dc['clasificacion']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Zona</label>
                <select name="id_zona" id="id-zona" class="form-select" required>
                    <option value="">Seleccionar zona</option>
                    <?php foreach ($zonas as $z): ?>
                        <option value="<?= $z['id_zona'] ?>"
                                data-centroid-lat="<?= htmlspecialchars($z['centroid_lat'] ?? '') ?>"
                                data-centroid-lng="<?= htmlspecialchars($z['centroid_lng'] ?? '') ?>"
                                <?= $incidencia['id_zona'] == $z['id_zona'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($z['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" rows="3" class="form-control" required><?= htmlspecialchars($incidencia['descripcion']) ?></textarea>
        </div>

        <div class="row mb-3">
            <div class="col-md-8">
                <label class="form-label">Dirección de la incidencia</label>
                <input type="text" name="direccion_incidencia" class="form-control" 
                       value="<?= htmlspecialchars($incidencia['direccion_incidencia']) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Prioridad</label>
                <select name="prioridad" class="form-select">
                    <option <?= $incidencia['prioridad'] === 'Baja' ? 'selected' : '' ?>>Baja</option>
                    <option <?= $incidencia['prioridad'] === 'Media' ? 'selected' : '' ?>>Media</option>
                    <option <?= $incidencia['prioridad'] === 'Alta' ? 'selected' : '' ?>>Alta</option>
                </select>
            </div>
        </div>

        <input type="hidden" name="latitud" value="<?= htmlspecialchars($incidencia['latitud'] ?? '') ?>">
        <input type="hidden" name="longitud" value="<?= htmlspecialchars($incidencia['longitud'] ?? '') ?>">
        <input type="hidden" name="geo_confidence" value="<?= htmlspecialchars($incidencia['geo_confidence'] ?? '') ?>">

        <div class="text-end">
            <button type="submit" class="btn btn-success px-4">
                <i class="bi bi-check-circle"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>

<script>
const tipoSelect = document.getElementById('tipo-delito');
const clasifSelect = document.getElementById('clasificacion-delito');
tipoSelect.addEventListener('change', () => {
    const tipo = tipoSelect.value;
    clasifSelect.querySelectorAll('option').forEach(opt => {
        opt.style.display = opt.dataset.tipo === tipo ? 'block' : 'none';
    });
    clasifSelect.value = '';
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

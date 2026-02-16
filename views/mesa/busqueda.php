<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h2 class="text-success mb-4">Búsqueda de Incidencias (Mesa de Partes)</h2>

    <form method="POST" class="card p-4 shadow-sm bg-light border-success mb-4">
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Tipo de delito</label>
                <select name="tipo_delito" class="form-select">
                    <option value="">Todos</option>
                    <?php foreach ($tipos as $t): ?>
                        <option value="<?= htmlspecialchars($t['tipo_delito']) ?>">
                            <?= htmlspecialchars($t['tipo_delito']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Recibido">Recibido</option>
                    <option value="Investigando">Investigando</option>
                    <option value="Resuelto">Resuelto</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Zona</label>
                <select name="id_zona" class="form-select">
                    <option value="">Todas</option>
                    <?php foreach ($zonas as $z): ?>
                        <option value="<?= $z['id_zona'] ?>"><?= htmlspecialchars($z['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Fecha desde</label>
                <input type="date" name="fecha_desde" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha hasta</label>
                <input type="date" name="fecha_hasta" class="form-control">
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success px-4">Buscar</button>
            <a href="<?= BASE_URL ?>/mesa/dashboard" class="btn btn-outline-secondary ms-2">← Volver</a>
        </div>
    </form>

    <?php if (!empty($resultados)): ?>
        <h3 class="text-success mb-3">Resultados (<?= count($resultados) ?>)</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-success">
                    <tr>
                        <th>ID</th>
                        <th>Tipo de Delito</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $inc): ?>
                        <tr>
                            <td><?= htmlspecialchars($inc['id_incidencia']) ?></td>
                            <td><?= htmlspecialchars($inc['tipo_delito']) ?></td>
                            <td><?= htmlspecialchars($inc['direccion_incidencia']) ?></td>
                            <td><?= htmlspecialchars($inc['estado']) ?></td>
                            <td><?= htmlspecialchars($inc['prioridad']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="alert alert-warning text-center">No se encontraron incidencias con los filtros aplicados.</div>
    <?php endif; ?>
</div>

<script src="<?= BASE_URL ?>/js/busqueda.js"></script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">

    <h2 class="text-success fw-bold mb-4">
        <i class="bi bi-search"></i> Búsqueda de Casos (SEINCRI)
    </h2>

    <form method="POST" class="row g-3 mb-4">

        <div class="col-md-3">
            <label class="form-label fw-bold">Tipo de Delito</label>
            <select name="tipo_delito" class="form-select">
                <option value="">Todos</option>
                <?php foreach ($tipos as $t): ?>
                    <option value="<?= htmlspecialchars($t['tipo_delito']) ?>"><?= htmlspecialchars($t['tipo_delito']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold">Estado</label>
            <select name="estado" class="form-select">
                <option value="">Todos</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Recibido">Recibido</option>
                <option value="Investigando">Investigando</option>
                <option value="Resuelto">Resuelto</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold">Zona</label>
            <select name="id_zona" class="form-select">
                <option value="">Todas</option>
                <?php foreach ($zonas as $z): ?>
                    <option value="<?= $z['id_zona'] ?>"><?= htmlspecialchars($z['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold">Fecha desde</label>
            <input type="date" name="fecha_desde" class="form-control">
        </div>

        <div class="col-md-2">
            <label class="form-label fw-bold">Fecha hasta</label>
            <input type="date" name="fecha_hasta" class="form-control">
        </div>

        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-success w-100">Buscar</button>
        </div>

    </form>

    <?php if (!empty($resultados)): ?>
        <h4 class="text-success fw-bold mb-3">Resultados (<?= count($resultados) ?>)</h4>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>ID</th>
                        <th>Tipo de Delito</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $inc): ?>
                        <tr>
                            <td class="fw-bold text-success"><?= htmlspecialchars($inc['id_incidencia']) ?></td>
                            <td><?= htmlspecialchars($inc['tipo_delito']) ?></td>
                            <td><?= htmlspecialchars($inc['direccion_incidencia']) ?></td>
                            <td>
                                <?php
                                    $estadoClass = match($inc['estado']) {
                                        'Pendiente' => 'bg-warning text-dark',
                                        'Recibido' => 'bg-primary text-white',
                                        'Investigando' => 'bg-info text-white',
                                        'Resuelto' => 'bg-success text-white',
                                        default => 'bg-secondary text-white',
                                    };
                                ?>
                                <span class="badge <?= $estadoClass ?>"><?= htmlspecialchars($inc['estado']) ?></span>
                            </td>
                            <td>
                                <a href="/seincri/detalle/<?= $inc['id_incidencia'] ?>" class="btn btn-sm btn-success">
                                    Ver/Actualizar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="alert alert-warning" role="alert">
            No se encontraron resultados para los filtros seleccionados.
        </div>
    <?php endif; ?>

   

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

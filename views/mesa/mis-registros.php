<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/layout.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/css/dashboard_mesa.css">

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title">
                <h1 class="page-title">
                    <i class="bi bi-folder2-open text-success"></i> Mis Registros de Incidencias
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-info-circle"></i> Total de registros: <strong><?= count($registros) ?></strong>
                </p>
            </div>
            <div class="header-actions">
                <a href="<?= BASE_URL ?>/mesa/registro" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Nueva Incidencia
                </a>
                
            </div>
        </div>
        <hr class="divider">
    </div>

    <!-- Filtros de búsqueda -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="Pendiente" <?= ($_GET['estado'] ?? '') === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="Recibido" <?= ($_GET['estado'] ?? '') === 'Recibido' ? 'selected' : '' ?>>Recibido</option>
                        <option value="Investigando" <?= ($_GET['estado'] ?? '') === 'Investigando' ? 'selected' : '' ?>>Investigando</option>
                        <option value="Resuelto" <?= ($_GET['estado'] ?? '') === 'Resuelto' ? 'selected' : '' ?>>Resuelto</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Tipo de Delito</label>
                    <select name="tipo_delito" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <?php foreach ($tiposDelito as $tipo): ?>
                            <option value="<?= htmlspecialchars($tipo) ?>" 
                                    <?= ($_GET['tipo_delito'] ?? '') === $tipo ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tipo) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Fecha Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" 
                           value="<?= htmlspecialchars($_GET['fecha_desde'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Fecha Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" 
                           value="<?= htmlspecialchars($_GET['fecha_hasta'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                    <a href="<?= BASE_URL ?>/mesa/mis-registros" class="btn btn-outline-secondary btn-sm w-100 mt-1">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de registros -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (!empty($registros)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-head">
                            <tr>
                                <th class="table-th"><i class="bi bi-hash"></i> ID</th>
                                <th class="table-th"><i class="bi bi-file-text"></i> Tipo de Delito</th>
                                <th class="table-th"><i class="bi bi-geo-alt"></i> Dirección</th>
                                <th class="table-th"><i class="bi bi-flag"></i> Estado</th>
                                <th class="table-th"><i class="bi bi-speedometer"></i> Prioridad</th>
                                <th class="table-th"><i class="bi bi-calendar"></i> Fecha</th>
                                <th class="table-th text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($registros as $reg): ?>
                                <tr class="table-row">
                                    <td class="px-4">
                                        <span class="badge bg-light text-dark border">
                                            #<?= htmlspecialchars($reg['id_incidencia']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-shield-exclamation text-danger me-2"></i>
                                            <span class="fw-semibold"><?= htmlspecialchars($reg['tipo_delito']) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?= htmlspecialchars(substr($reg['direccion_incidencia'], 0, 40)) ?>...</small>
                                    </td>
                                    <td>
                                        <?php
                                        $badgeClass = match($reg['estado']) {
                                            'Pendiente' => 'bg-warning text-dark',
                                            'Recibido' => 'bg-info',
                                            'Investigando' => 'bg-primary',
                                            'Resuelto' => 'bg-success',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeClass ?> px-2 py-1">
                                            <?= htmlspecialchars($reg['estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $prioridadClass = match($reg['prioridad']) {
                                            'Alta' => 'bg-danger',
                                            'Media' => 'bg-warning text-dark',
                                            'Baja' => 'bg-secondary',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $prioridadClass ?>"><?= htmlspecialchars($reg['prioridad']) ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($reg['fecha_registro'])) ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?= BASE_URL ?>/mesa/detalle/<?= $reg['id_incidencia'] ?>" 
                                               class="btn btn-outline-primary" title="Ver detalles">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if ($reg['estado'] === 'Pendiente'): ?>
                                                <a href="<?= BASE_URL ?>/mesa/editar/<?= $reg['id_incidencia'] ?>" 
                                                   class="btn btn-outline-success" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?= BASE_URL ?>/mesa/eliminar/<?= $reg['id_incidencia'] ?>" 
                                                   class="btn btn-outline-danger" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-outline-secondary" disabled>
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-outline-secondary" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state py-5">
                    <i class="bi bi-inbox empty-icon"></i>
                    <p class="empty-text">No se encontraron registros con los filtros aplicados.</p>
                    <a href="<?= BASE_URL ?>/mesa/registro" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle"></i> Registrar nueva incidencia
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

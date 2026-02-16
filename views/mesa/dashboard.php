<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/layout.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/css/dashboard_mesa.css">

<div class="dashboard-container">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title">
                <h1 class="page-title">
                    <i class="bi bi-speedometer2 text-success"></i> Dashboard
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-info-circle"></i> Resumen general de incidencias registradas
                </p>
            </div>
            <div class="header-date">
                <small class="text-muted">
                    <i class="bi bi-calendar3"></i> <?= date('d/m/Y') ?>
                    <i class="bi bi-clock ms-2"></i> <?= date('H:i') ?>
                </small>
            </div>
        </div>
        <hr class="divider">
    </div>

    <!-- Indicadores principales (KPIs) -->
    <div class="kpi-section">
        <div class="row g-4">
            <!-- Incidencias de hoy -->
            <div class="col-xl-4 col-md-6">
                <div class="kpi-card card-success">
                    <div class="card-body">
                        <div class="kpi-content">
                            <div class="kpi-info">
                                <div class="kpi-label">Incidencias Hoy</div>
                                <div class="kpi-value"><?= count($incidenciasHoy) ?></div>
                                <small class="kpi-description">
                                    <i class="bi bi-calendar-check"></i> Registradas hoy
                                </small>
                            </div>
                            <div class="kpi-icon icon-success">
                                <i class="bi bi-calendar-day"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> Actualizado en tiempo real
                        </small>
                    </div>
                </div>
            </div>

            <!-- Total de incidencias -->
            <div class="col-xl-4 col-md-6">
                <div class="kpi-card card-primary">
                    <div class="card-body">
                        <div class="kpi-content">
                            <div class="kpi-info">
                                <div class="kpi-label">Total Incidencias</div>
                                <div class="kpi-value"><?= count($todas ?? []) ?></div>
                                <small class="kpi-description">
                                    <i class="bi bi-database"></i> Registros totales
                                </small>
                            </div>
                            <div class="kpi-icon icon-primary">
                                <i class="bi bi-list-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="text-primary">
                            <i class="bi bi-graph-up"></i> Histórico completo
                        </small>
                    </div>
                </div>
            </div>

            <!-- Pendientes -->
            <div class="col-xl-4 col-md-6">
                <div class="kpi-card card-warning">
                    <div class="card-body">
                        <div class="kpi-content">
                            <div class="kpi-info">
                                <div class="kpi-label">Pendientes</div>
                                <div class="kpi-value"><?= count($pendientes ?? []) ?></div>
                                <small class="kpi-description">
                                    <i class="bi bi-exclamation-circle"></i> Requieren atención
                                </small>
                            </div>
                            <div class="kpi-icon icon-warning">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="text-warning">
                            <i class="bi bi-bell"></i> Acción requerida
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de últimos registros -->
    <div class="table-section">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="table-header">
                    <h5 class="table-title">
                        <i class="bi bi-clock-history text-success"></i> Últimos Registros
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="<?= BASE_URL ?>/mesa/mis-registros" class="btn btn-sm btn-success">
                            <i class="bi bi-folder2-open"></i> Ver todos mis registros
                        </a>
                        <a href="<?= BASE_URL ?>/mesa/registro" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-plus-circle"></i> Nueva Incidencia
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($ultimos)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-head">
                                <tr>
                                    <th class="table-th">
                                        <i class="bi bi-hash"></i> ID
                                    </th>
                                    <th class="table-th">
                                        <i class="bi bi-file-text"></i> Tipo de Delito
                                    </th>
                                    <th class="table-th">
                                        <i class="bi bi-flag"></i> Estado
                                    </th>
                                    <th class="table-th">
                                        <i class="bi bi-calendar"></i> Fecha Registro
                                    </th>
                                    <th class="table-th text-center">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($ultimos, 0, 10) as $inc): ?>
                                    <tr class="table-row">
                                        <td class="px-4">
                                            <span class="badge bg-light text-dark border">
                                                #<?= htmlspecialchars($inc['id_incidencia']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-shield-exclamation text-danger me-2"></i>
                                                <span class="fw-semibold">
                                                    <?= htmlspecialchars($inc['tipo_delito']) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = match($inc['estado']) {
                                                'Pendiente' => 'bg-warning text-dark',
                                                'Recibido' => 'bg-info',
                                                'Investigando' => 'bg-primary',
                                                'Resuelto' => 'bg-success',
                                                default => 'bg-secondary'
                                            };
                                            $icon = match($inc['estado']) {
                                                'Pendiente' => 'bi-clock',
                                                'Recibido' => 'bi-check-circle',
                                                'Investigando' => 'bi-arrow-repeat',
                                                'Resuelto' => 'bi-check-circle-fill',
                                                default => 'bi-dash-circle'
                                            };
                                            ?>
                                            <span class="badge <?= $badgeClass ?> px-3 py-2">
                                                <i class="bi <?= $icon ?>"></i>
                                                <?= htmlspecialchars($inc['estado']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar3"></i>
                                                <?= date('d/m/Y H:i', strtotime($inc['fecha_registro'])) ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <!-- Ver Detalles -->
                                                <a href="<?= BASE_URL ?>/mesa/detalle/<?= $inc['id_incidencia'] ?>" 
                                                   class="btn btn-outline-primary" 
                                                   title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                
                                                <!-- Editar (solo si está Pendiente) -->
                                                <?php if ($inc['estado'] === 'Pendiente'): ?>
                                                    <a href="<?= BASE_URL ?>/mesa/editar/<?= $inc['id_incidencia'] ?>" 
                                                       class="btn btn-outline-success" 
                                                       title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    
                                                    <!-- Eliminar (solo si está Pendiente) -->
                                                    <a href="<?= BASE_URL ?>/mesa/eliminar/<?= $inc['id_incidencia'] ?>" 
                                                       class="btn btn-outline-danger" 
                                                       title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <!-- Botones deshabilitados si no está Pendiente -->
                                                    <button class="btn btn-outline-secondary" disabled title="No editable">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-outline-secondary" disabled title="No se puede eliminar">
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
                    <div class="empty-state">
                        <i class="bi bi-inbox empty-icon"></i>
                        <p class="empty-text">No se encontraron registros recientes.</p>
                        <a href="<?= BASE_URL ?>/mesa/registro" class="btn btn-success btn-sm">
                            <i class="bi bi-plus-circle"></i> Registrar nueva incidencia
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

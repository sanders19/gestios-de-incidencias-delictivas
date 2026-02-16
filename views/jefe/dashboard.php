<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/layout.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/css/dashboard_mesa.css">

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title">
                <h1 class="page-title">
                    <i class="bi bi-speedometer2 text-success"></i> Dashboard - Jefatura
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-info-circle"></i> Panel de control y gestión general del sistema
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

    <!-- KPIs Principales -->
    <div class="kpi-section">
        <div class="row g-4">
            <!-- Total Incidencias -->
            <div class="col-xl-3 col-md-6">
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
                            <i class="bi bi-graph-up"></i> Todas las incidencias
                        </small>
                    </div>
                </div>
            </div>

            <!-- Pendientes -->
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card card-warning">
                    <div class="card-body">
                        <div class="kpi-content">
                            <div class="kpi-info">
                                <div class="kpi-label">Pendientes</div>
                                <div class="kpi-value"><?= count($pendientes ?? []) ?></div>
                                <small class="kpi-description">
                                    <i class="bi bi-exclamation-circle"></i> Sin asignar
                                </small>
                            </div>
                            <div class="kpi-icon icon-warning">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="<?= BASE_URL ?>/jefe/asignacion" class="text-warning text-decoration-none">
                            <i class="bi bi-arrow-right-circle"></i> Asignar casos
                        </a>
                    </div>
                </div>
            </div>

            <!-- En Proceso -->
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card card-info">
                    <div class="card-body">
                        <div class="kpi-content">
                            <div class="kpi-info">
                                <div class="kpi-label">En Proceso</div>
                                <div class="kpi-value">
                                    <?= count(array_filter($todas ?? [], fn($i) => in_array($i['estado'], ['Recibido', 'Investigando']))) ?>
                                </div>
                                <small class="kpi-description">
                                    <i class="bi bi-arrow-repeat"></i> En investigación
                                </small>
                            </div>
                            <div class="kpi-icon icon-info">
                                <i class="bi bi-gear-fill"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="text-info">
                            <i class="bi bi-clock-history"></i> Asignadas a SEINCRI
                        </small>
                    </div>
                </div>
            </div>

            <!-- Resueltas -->
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card card-success">
                    <div class="card-body">
                        <div class="kpi-content">
                            <div class="kpi-info">
                                <div class="kpi-label">Resueltas</div>
                                <div class="kpi-value"><?= count($resueltas ?? []) ?></div>
                                <small class="kpi-description">
                                    <i class="bi bi-check-circle"></i> Casos cerrados
                                </small>
                            </div>
                            <div class="kpi-icon icon-success">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <small class="text-success">
                            <i class="bi bi-trophy"></i> 
                            <?= count($todas ?? []) > 0 ? round((count($resueltas ?? []) / count($todas ?? [])) * 100, 1) : 0 ?>% de efectividad
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>/jefe/asignacion" class="btn btn-primary w-100 py-3">
                <i class="bi bi-clipboard-check fs-4 d-block mb-2"></i>
                <span class="fw-semibold">Asignar Casos</span>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>/jefe/usuarios" class="btn btn-outline-success w-100 py-3">
                <i class="bi bi-people fs-4 d-block mb-2"></i>
                <span class="fw-semibold">Ver Usuarios</span>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>/jefe/crear_usuario" class="btn btn-success w-100 py-3">
                <i class="bi bi-person-plus fs-4 d-block mb-2"></i>
                <span class="fw-semibold">Crear Usuario</span>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?= BASE_URL ?>/jefe/reportes" class="btn btn-outline-primary w-100 py-3">
                <i class="bi bi-file-earmark-bar-graph fs-4 d-block mb-2"></i>
                <span class="fw-semibold">Reportes</span>
            </a>
        </div>
    </div>

    <!-- Estadísticas por Agente SEINCRI -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-person-badge text-success"></i> Rendimiento por Agente SEINCRI
                </h5>
                <a href="<?= BASE_URL ?>/jefe/historial-asignaciones" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-clock-history"></i> Ver Historial
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($agentes)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-head">
                            <tr>
                                <th class="table-th"><i class="bi bi-person"></i> Agente</th>
                                <th class="table-th text-center"><i class="bi bi-list-task"></i> Total Asignados</th>
                                <th class="table-th text-center"><i class="bi bi-check-circle"></i> Resueltos</th>
                                <th class="table-th text-center"><i class="bi bi-percent"></i> Efectividad</th>
                                <th class="table-th text-center"><i class="bi bi-graph-up"></i> Progreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($agentes as $id => $stats): ?>
                                <?php
                                $porcentaje = $stats['total'] > 0 ? round(($stats['resueltos'] / $stats['total']) * 100, 1) : 0;
                                $colorBarra = $porcentaje >= 70 ? 'bg-success' : ($porcentaje >= 40 ? 'bg-warning' : 'bg-danger');
                                ?>
                                <tr>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2">
                                                <i class="bi bi-person-check text-success"></i>
                                            </div>
                                            <span class="fw-semibold"><?= htmlspecialchars($id) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary"><?= $stats['total'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?= $stats['resueltos'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-semibold"><?= $porcentaje ?>%</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar <?= $colorBarra ?>" 
                                                 role="progressbar" 
                                                 style="width: <?= $porcentaje ?>%"
                                                 aria-valuenow="<?= $porcentaje ?>" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                <?= $porcentaje ?>%
                                            </div>
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
                    <p class="empty-text">No hay asignaciones registradas</p>
                    <a href="<?= BASE_URL ?>/jefe/asignacion" class="btn btn-success btn-sm">
                        <i class="bi bi-clipboard-check"></i> Asignar primera incidencia
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

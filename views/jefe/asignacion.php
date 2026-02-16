<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/layout.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/css/dashboard_mesa.css">

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title">
                <h1 class="page-title">
                    <i class="bi bi-clipboard-check text-primary"></i> Asignación de Incidencias
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-info-circle"></i> Asignar casos pendientes a investigadores SEINCRI
                </p>
            </div>
            <div class="header-actions">
                <a href="<?= BASE_URL ?>/jefe/historial-asignaciones" class="btn btn-outline-primary">
                    <i class="bi bi-clock-history"></i> Ver Historial
                </a>
                <a href="<?= BASE_URL ?>/jefe/dashboard" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        <hr class="divider">
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-warning mb-1 fw-bold"><?= count($pendientes) ?></h3>
                            <small class="text-muted">Casos Pendientes</small>
                        </div>
                        <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-success mb-1 fw-bold"><?= count($seincris) ?></h3>
                            <small class="text-muted">Investigadores Disponibles</small>
                        </div>
                        <i class="bi bi-person-badge-fill text-success" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info bg-opacity-10 border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="text-info mb-1 fw-bold">
                                <?= count($seincris) > 0 ? round(count($pendientes) / count($seincris), 1) : 0 ?>
                            </h3>
                            <small class="text-muted">Casos por Investigador</small>
                        </div>
                        <i class="bi bi-graph-up-arrow text-info" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de incidencias pendientes -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-task"></i> Incidencias Pendientes de Asignación
                </h5>
                <span class="badge bg-white text-primary">
                    <?= count($pendientes) ?> caso<?= count($pendientes) != 1 ? 's' : '' ?>
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($pendientes)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3"><i class="bi bi-hash"></i> ID</th>
                                <th class="py-3"><i class="bi bi-file-text"></i> Tipo de Delito</th>
                                <th class="py-3"><i class="bi bi-geo-alt"></i> Ubicación</th>
                                <th class="py-3 text-center"><i class="bi bi-speedometer"></i> Prioridad</th>
                                <th class="py-3"><i class="bi bi-calendar"></i> Fecha</th>
                                <th class="py-3"><i class="bi bi-person"></i> Registrado Por</th>
                                <th class="py-3 text-center" style="min-width: 320px;"><i class="bi bi-clipboard-check"></i> Asignar a</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendientes as $inc): ?>
                                <tr class="border-bottom">
                                    <td class="px-4">
                                        <span class="badge bg-light text-dark border fs-6">
                                            #<?= htmlspecialchars($inc['id_incidencia']) ?>
                                        </span>
                                    </td>
                                    <td style="max-width: 280px;">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-shield-exclamation text-danger me-2 mt-1" style="font-size: 1.2rem;"></i>
                                            <div>
                                                <span class="fw-semibold d-block text-dark"><?= htmlspecialchars($inc['tipo_delito']) ?></span>
                                                <small class="text-muted" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                    <?= htmlspecialchars($inc['descripcion'] ?? 'Sin descripción') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <small class="text-dark">
                                                <i class="bi bi-geo-alt-fill text-primary"></i>
                                                <strong>Zona:</strong> <?= htmlspecialchars($inc['zona_nombre'] ?? 'N/A') ?>
                                            </small>
                                            <small class="text-muted">
                                                <?= htmlspecialchars(substr($inc['direccion_incidencia'] ?? 'Sin dirección', 0, 30)) ?>...
                                            </small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $prioridadConfig = match($inc['prioridad']) {
                                            'Alta' => ['class' => 'bg-danger', 'icon' => 'bi-exclamation-circle-fill'],
                                            'Media' => ['class' => 'bg-warning text-dark', 'icon' => 'bi-dash-circle-fill'],
                                            'Baja' => ['class' => 'bg-secondary', 'icon' => 'bi-circle-fill'],
                                            default => ['class' => 'bg-secondary', 'icon' => 'bi-circle-fill']
                                        };
                                        ?>
                                        <span class="badge <?= $prioridadConfig['class'] ?> px-3 py-2">
                                            <i class="bi <?= $prioridadConfig['icon'] ?> me-1"></i>
                                            <?= htmlspecialchars($inc['prioridad']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted d-block">
                                            <i class="bi bi-calendar3"></i>
                                            <?= date('d/m/Y', strtotime($inc['fecha_registro'])) ?>
                                        </small>
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i>
                                            <?= date('H:i', strtotime($inc['fecha_registro'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.75rem; font-weight: bold;">
                                                <?= strtoupper(substr($inc['registrado_por_nombre'] ?? $inc['registrado_por'], 0, 2)) ?>
                                            </div>
                                            <small class="text-dark">
                                                <?= htmlspecialchars($inc['registrado_por_nombre'] ?? $inc['registrado_por']) ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <form method="POST" action="<?= BASE_URL ?>/jefe/asignacion/asignar/<?= $inc['id_incidencia'] ?>" class="d-flex gap-2 align-items-center justify-content-center">
                                            <select name="asignado_a" class="form-select form-select-sm" style="width: 220px;" required>
                                                <option value="">🔍 Seleccionar SEINCRI...</option>
                                                <?php foreach ($seincris as $s): ?>
                                                    <option value="<?= $s['id_usuario'] ?>">
                                                        👤 <?= htmlspecialchars($s['nombre_completo']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-success px-3">
                                                <i class="bi bi-check-circle-fill"></i> Asignar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state py-5">
                    <div class="text-center">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem; opacity: 0.3;"></i>
                        <h4 class="text-success mt-3">¡Todo asignado!</h4>
                        <p class="text-muted mb-0">No hay incidencias pendientes de asignación</p>
                        <small class="text-muted">Todas las incidencias han sido distribuidas a los investigadores</small>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Información adicional -->
    <?php if (!empty($pendientes)): ?>
    <div class="alert alert-info mt-4 border-0 shadow-sm" role="alert">
        <div class="d-flex align-items-start">
            <i class="bi bi-info-circle-fill me-3" style="font-size: 1.5rem;"></i>
            <div>
                <h6 class="alert-heading mb-2">Instrucciones de Asignación</h6>
                <ul class="mb-0 small">
                    <li>Selecciona un investigador SEINCRI disponible del menú desplegable</li>
                    <li>Los casos se asignarán automáticamente y cambiarán al estado <strong>"Recibido"</strong></li>
                    <li>El investigador recibirá notificación del caso asignado</li>
                    <li>Puedes ver el historial completo en la sección <strong>"Ver Historial"</strong></li>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

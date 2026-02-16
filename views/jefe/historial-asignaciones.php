<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/layout.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/css/dashboard_mesa.css">

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title">
                <h1 class="page-title">
                    <i class="bi bi-clock-history text-primary"></i> Historial de Asignaciones
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-info-circle"></i> Registro de casos asignados a SEINCRI
                </p>
            </div>
            <div class="header-actions">
                <a href="<?= BASE_URL ?>/jefe/asignacion" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver a Asignación
                </a>
            </div>
        </div>
        <hr class="divider">
    </div>

    <!-- Tabla de historial -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">
                <i class="bi bi-list-check"></i> Últimas Asignaciones
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($historial)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-head">
                            <tr>
                                <th class="table-th"><i class="bi bi-hash"></i> ID Caso</th>
                                <th class="table-th"><i class="bi bi-person"></i> Denunciante</th>
                                <th class="table-th"><i class="bi bi-exclamation-triangle"></i> Delito</th>
                                <th class="table-th"><i class="bi bi-person-badge"></i> Asignado a (SEINCRI)</th>
                                <th class="table-th"><i class="bi bi-calendar"></i> Fecha Asignación</th>
                                <th class="table-th text-center"><i class="bi bi-info-circle"></i> Estado Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historial as $h): ?>
                                <tr>
                                    <td class="px-4">
                                        <span class="badge bg-light text-dark border">
                                            #<?= $h['id_incidencia'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                            $nombreCompleto = htmlspecialchars($h['denunciante_nombre'] . ' ' . 
                                                              $h['apellido_paterno'] . ' ' . 
                                                              $h['apellido_materno']);
                                        ?>
                                        <strong><?= htmlspecialchars($h['denunciante_nombre']) ?></strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($h['tipo_delito']) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <i class="bi bi-person-badge text-success me-1"></i>
                                        <?= htmlspecialchars($h['seincri_nombre']) ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($h['fecha_asignacion'])) ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            $badgeClass = '';
                                            switch($h['estado']) {
                                                case 'Pendiente':
                                                    $badgeClass = 'bg-warning';
                                                    break;
                                                case 'Recibido':
                                                    $badgeClass = 'bg-info';
                                                    break;
                                                case 'En Proceso':
                                                    $badgeClass = 'bg-primary';
                                                    break;
                                                case 'Resuelto':
                                                    $badgeClass = 'bg-success';
                                                    break;
                                                case 'Archivado':
                                                    $badgeClass = 'bg-secondary';
                                                    break;
                                                default:
                                                    $badgeClass = 'bg-light text-dark';
                                            }
                                        ?>
                                        <span class="badge <?= $badgeClass ?>">
                                            <?= htmlspecialchars($h['estado']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state py-5">
                    <i class="bi bi-inbox empty-icon"></i>
                    <p class="empty-text">No hay registros de asignaciones</p>
                    <small class="text-muted">Las asignaciones aparecerán aquí una vez que se realicen</small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/layout.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/css/dashboard_mesa.css">

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title">
                <h1 class="page-title">
                    <i class="bi bi-journal-check text-success"></i> Mis Casos Asignados
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-info-circle"></i> Casos que requieren tu atención
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

    <!-- Estadísticas rápidas -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="text-primary mb-0"><?= count($incidencias ?? []) ?></h3>
                    <small class="text-muted">Total Asignados</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="text-warning mb-0">
                        <?= count(array_filter($incidencias ?? [], fn($i) => in_array($i['estado'], ['Recibido', 'Investigando']))) ?>
                    </h3>
                    <small class="text-muted">En Proceso</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="text-success mb-0">
                        <?= count(array_filter($incidencias ?? [], fn($i) => $i['estado'] === 'Resuelto')) ?>
                    </h3>
                    <small class="text-muted">Resueltos</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de casos -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0"><i class="bi bi-list-task"></i> Lista de Casos</h5>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($incidencias)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-head">
                            <tr>
                                <th class="table-th"><i class="bi bi-hash"></i> ID</th>
                                <th class="table-th"><i class="bi bi-file-text"></i> Tipo de Delito</th>
                                <th class="table-th"><i class="bi bi-geo-alt"></i> Dirección</th>
                                <th class="table-th"><i class="bi bi-flag"></i> Estado</th>
                                <th class="table-th"><i class="bi bi-speedometer"></i> Prioridad</th>
                                <th class="table-th text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($incidencias as $inc): ?>
                                <tr>
                                    <td class="px-4">
                                        <span class="badge bg-light text-dark border">
                                            #<?= htmlspecialchars($inc['id_incidencia']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-shield-exclamation text-danger me-2"></i>
                                            <span class="fw-semibold"><?= htmlspecialchars($inc['tipo_delito']) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= htmlspecialchars(substr($inc['direccion_incidencia'], 0, 40)) ?>...
                                        </small>
                                    </td>
                                    <td>
                                        <?php
                                        $estadoClass = match($inc['estado']) {
                                            'Recibido' => 'bg-info',
                                            'Investigando' => 'bg-primary',
                                            'Resuelto' => 'bg-success',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $estadoClass ?> px-3 py-2">
                                            <?= htmlspecialchars($inc['estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $prioridadClass = match($inc['prioridad']) {
                                            'Alta' => 'bg-danger',
                                            'Media' => 'bg-warning text-dark',
                                            'Baja' => 'bg-secondary',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $prioridadClass ?>"><?= htmlspecialchars($inc['prioridad']) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= BASE_URL ?>/seincri/detalle/<?= $inc['id_incidencia'] ?>" 
                                           class="btn btn-sm btn-success">
                                            <i class="bi bi-eye"></i> Ver/Actualizar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state py-5">
                    <i class="bi bi-inbox empty-icon"></i>
                    <p class="empty-text">No tienes casos asignados</p>
                    <small class="text-muted">El jefe te asignará casos pronto</small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

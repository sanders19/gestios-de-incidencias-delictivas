<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/layout.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/css/dashboard_mesa.css">

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title">
                <h1 class="page-title">
                    <i class="bi bi-graph-up text-success"></i> Mis Estadísticas de Registro
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-info-circle"></i> Análisis de productividad y registros realizados
                </p>
            </div>
            <div class="header-actions">
                <a href="<?= BASE_URL ?>/mesa/dashboard" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        <hr class="divider">
    </div>

    <!-- Formulario de filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtros</h5>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar-range"></i> Periodo
                    </label>
                    <select name="periodo" class="form-select">
                        <option value="7 días" <?= ($filtros['periodo'] ?? '') === '7 días' ? 'selected' : '' ?>>Últimos 7 días</option>
                        <option value="Mes" <?= ($filtros['periodo'] ?? 'Mes') === 'Mes' ? 'selected' : '' ?>>Este mes</option>
                        <option value="Trimestre" <?= ($filtros['periodo'] ?? '') === 'Trimestre' ? 'selected' : '' ?>>Trimestre</option>
                        <option value="Año" <?= ($filtros['periodo'] ?? '') === 'Año' ? 'selected' : '' ?>>Año</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-file-text"></i> Tipo de Delito
                    </label>
                    <select name="tipo_delito" class="form-select">
                        <option value="">Todos</option>
                        <?php foreach ($tipos as $t): ?>
                            <option value="<?= htmlspecialchars($t['tipo_delito']) ?>" 
                                <?= ($filtros['tipo_delito'] ?? '') === $t['tipo_delito'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['tipo_delito']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-geo-alt"></i> Zona
                    </label>
                    <select name="id_zona" class="form-select">
                        <option value="">Todas</option>
                        <?php foreach ($zonas as $z): ?>
                            <option value="<?= $z['id_zona'] ?>" 
                                <?= ($filtros['id_zona'] ?? '') == $z['id_zona'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($z['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-search"></i> Generar Estadísticas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($estadisticas)): ?>
        <!-- KPIs Principales -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                    <div class="card-body text-center">
                        <i class="bi bi-clipboard-check text-success fs-1"></i>
                        <h3 class="text-success mb-0 mt-2"><?= $estadisticas['total'] ?></h3>
                        <small class="text-muted fw-semibold">Total Registrados</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                    <div class="card-body text-center">
                        <i class="bi bi-hourglass-split text-warning fs-1"></i>
                        <h3 class="text-warning mb-0 mt-2"><?= $estadisticas['pendientes_asignacion'] ?></h3>
                        <small class="text-muted fw-semibold">Pendientes de Asignación</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar3 text-primary fs-1"></i>
                        <h3 class="text-primary mb-0 mt-2"><?= $estadisticas['promedio_diario'] ?></h3>
                        <small class="text-muted fw-semibold">Promedio Diario</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                    <div class="card-body text-center">
                        <i class="bi bi-ui-checks text-info fs-1"></i>
                        <h3 class="text-info mb-0 mt-2"><?= count($estadisticas['por_tipo']) ?></h3>
                        <small class="text-muted fw-semibold">Tipos de Delito</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Incidencias Pendientes de Asignación -->
        <?php if (!empty($estadisticas['pendientes_lista'])): ?>
        <div class="alert alert-warning border-0 shadow-sm mb-4">
            <h5 class="alert-heading">
                <i class="bi bi-exclamation-triangle-fill"></i> Incidencias Pendientes de Asignación
            </h5>
            <p class="mb-2">Tienes <strong><?= $estadisticas['pendientes_asignacion'] ?> incidencia(s)</strong> registradas que aún no han sido asignadas a SEINCRI:</p>
            <ul class="mb-0">
                <?php foreach ($estadisticas['pendientes_lista'] as $caso): ?>
                    <li>
                        <strong>#<?= htmlspecialchars($caso['id_incidencia']) ?></strong> - 
                        <?= htmlspecialchars($caso['tipo_delito']) ?> 
                        <small class="text-muted">(<?= date('d/m/Y', strtotime($caso['fecha_registro'])) ?>)</small>
                        <a href="<?= BASE_URL ?>/mesa/detalle/<?= $caso['id_incidencia'] ?>" class="btn btn-sm btn-warning ms-2">
                            <i class="bi bi-eye"></i> Ver
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php if ($estadisticas['pendientes_asignacion'] > 5): ?>
                <p class="mt-2 mb-0">
                    <a href="<?= BASE_URL ?>/mesa/mis-registros" class="btn btn-sm btn-warning">
                        <i class="bi bi-list"></i> Ver todos los pendientes
                    </a>
                </p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Productividad Diaria -->
            <?php if (!empty($estadisticas['registros_por_dia'])): ?>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0"><i class="bi bi-calendar-week"></i> Productividad Diaria</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th class="text-center">Registros</th>
                                        <th style="width: 50%;">Visual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $max = max($estadisticas['registros_por_dia']);
                                    foreach ($estadisticas['registros_por_dia'] as $fecha => $cantidad): 
                                        $porcentaje = $max > 0 ? round(($cantidad / $max) * 100) : 0;
                                    ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($fecha)) ?></td>
                                            <td class="text-center"><span class="badge bg-success"><?= $cantidad ?></span></td>
                                            <td>
                                                <div class="progress" style="height: 18px;">
                                                    <div class="progress-bar bg-success" style="width: <?= $porcentaje ?>%">
                                                        <?= $cantidad ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Distribución por Prioridad -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0"><i class="bi bi-speedometer"></i> Distribución por Prioridad</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <tbody>
                                    <?php foreach ($estadisticas['por_prioridad'] as $prioridad => $cantidad): ?>
                                        <?php
                                        $porcentaje = $estadisticas['total'] > 0 
                                            ? round(($cantidad / $estadisticas['total']) * 100, 1) 
                                            : 0;
                                        $colorBarra = match($prioridad) {
                                            'Alta' => 'bg-danger',
                                            'Media' => 'bg-warning',
                                            'Baja' => 'bg-secondary',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <tr>
                                            <td class="fw-semibold"><?= htmlspecialchars($prioridad) ?></td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar <?= $colorBarra ?>" 
                                                         style="width: <?= $porcentaje ?>%">
                                                        <?= $cantidad ?> (<?= $porcentaje ?>%)
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 10 Tipos de Delito -->
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0"><i class="bi bi-file-text"></i> Top 10 Tipos de Delito Registrados</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tipo de Delito</th>
                                        <th class="text-center">Cantidad</th>
                                        <th style="width: 40%;">Distribución</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    arsort($estadisticas['por_tipo']);
                                    $contador = 0;
                                    foreach ($estadisticas['por_tipo'] as $tipo => $cantidad):
                                        if ($contador >= 10) break;
                                        $porcentaje = round(($cantidad / $estadisticas['total']) * 100, 1);
                                        $contador++;
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($tipo) ?></td>
                                            <td class="text-center"><span class="badge bg-success"><?= $cantidad ?></span></td>
                                            <td>
                                                <div class="progress" style="height: 18px;">
                                                    <div class="progress-bar bg-success" style="width: <?= $porcentaje ?>%">
                                                        <?= $porcentaje ?>%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Distribución por Zona -->
            <?php if (!empty($estadisticas['por_zona'])): ?>
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0"><i class="bi bi-geo-alt"></i> Distribución por Zona</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Zona</th>
                                        <th class="text-center">Cantidad</th>
                                        <th style="width: 40%;">Distribución</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    arsort($estadisticas['por_zona']);
                                    foreach ($estadisticas['por_zona'] as $zona_id => $cantidad):
                                        $zona_nombre = $zonasNombres[$zona_id] ?? "Zona $zona_id";
                                        $porcentaje = round(($cantidad / $estadisticas['total']) * 100, 1);
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($zona_nombre) ?></td>
                                            <td class="text-center"><span class="badge bg-primary"><?= $cantidad ?></span></td>
                                            <td>
                                                <div class="progress" style="height: 18px;">
                                                    <div class="progress-bar bg-primary" style="width: <?= $porcentaje ?>%">
                                                        <?= $porcentaje ?>%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="alert alert-warning border-0 shadow-sm">
            <i class="bi bi-info-circle"></i> No se encontraron datos para los filtros seleccionados.
        </div>
    <?php else: ?>
        <div class="alert alert-info border-0 shadow-sm">
            <i class="bi bi-info-circle"></i> Selecciona los filtros y haz clic en <strong>"Generar Estadísticas"</strong> para ver tu productividad de registro.
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

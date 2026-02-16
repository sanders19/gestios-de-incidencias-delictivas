<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/layout.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/css/dashboard_mesa.css">

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title">
                <h1 class="page-title">
                    <i class="bi bi-bar-chart-line text-success"></i> Mis Estadísticas
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-info-circle"></i> Análisis de rendimiento de mis casos asignados
                </p>
            </div>
            <div class="header-actions">
                <a href="<?= BASE_URL ?>/seincri/dashboard" class="btn btn-outline-secondary">
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
                <div class="col-md-3">
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

                <div class="col-md-3">
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

                <div class="col-md-3">
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

                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-flag"></i> Estado
                    </label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="Recibido" <?= ($filtros['estado'] ?? '') === 'Recibido' ? 'selected' : '' ?>>Recibido</option>
                        <option value="Investigando" <?= ($filtros['estado'] ?? '') === 'Investigando' ? 'selected' : '' ?>>Investigando</option>
                        <option value="Resuelto" <?= ($filtros['estado'] ?? '') === 'Resuelto' ? 'selected' : '' ?>>Resuelto</option>
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
                <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                    <div class="card-body text-center">
                        <i class="bi bi-list-check text-primary fs-1"></i>
                        <h3 class="text-primary mb-0 mt-2"><?= $estadisticas['total_asignados'] ?></h3>
                        <small class="text-muted fw-semibold">Total Asignados</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle-fill text-success fs-1"></i>
                        <h3 class="text-success mb-0 mt-2"><?= $estadisticas['resueltos'] ?></h3>
                        <small class="text-muted fw-semibold">Resueltos</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                    <div class="card-body text-center">
                        <i class="bi bi-hourglass-split text-warning fs-1"></i>
                        <h3 class="text-warning mb-0 mt-2"><?= $estadisticas['pendientes'] ?></h3>
                        <small class="text-muted fw-semibold">En Proceso</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                    <div class="card-body text-center">
                        <i class="bi bi-graph-up text-info fs-1"></i>
                        <h3 class="text-info mb-0 mt-2"><?= $estadisticas['tasa_resolucion'] ?>%</h3>
                        <small class="text-muted fw-semibold">Tasa de Resolución</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Casos Urgentes -->
        <?php if (!empty($estadisticas['casos_urgentes'])): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <h5 class="alert-heading">
                <i class="bi bi-exclamation-triangle-fill"></i> Casos Urgentes Sin Resolver
            </h5>
            <p class="mb-2">Tienes <strong><?= count($estadisticas['casos_urgentes']) ?> caso(s)</strong> de prioridad <strong>ALTA</strong> que requieren atención inmediata:</p>
            <ul class="mb-0">
                <?php foreach ($estadisticas['casos_urgentes'] as $caso): ?>
                    <li>
                        <strong>#<?= htmlspecialchars($caso['id_incidencia']) ?></strong> - 
                        <?= htmlspecialchars($caso['tipo_delito']) ?> 
                        <span class="badge bg-<?= $caso['estado'] === 'Investigando' ? 'primary' : 'info' ?>">
                            <?= htmlspecialchars($caso['estado']) ?>
                        </span>
                        <a href="<?= BASE_URL ?>/seincri/detalle/<?= $caso['id_incidencia'] ?>" class="btn btn-sm btn-danger ms-2">
                            <i class="bi bi-eye"></i> Ver
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Distribución por Estado -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0"><i class="bi bi-pie-chart"></i> Distribución por Estado</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <tbody>
                                    <?php foreach ($estadisticas['por_estado'] as $estado => $cantidad): ?>
                                        <?php
                                        $porcentaje = $estadisticas['total_asignados'] > 0 
                                            ? round(($cantidad / $estadisticas['total_asignados']) * 100, 1) 
                                            : 0;
                                        $colorBarra = match($estado) {
                                            'Recibido' => 'bg-info',
                                            'Investigando' => 'bg-primary',
                                            'Resuelto' => 'bg-success',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <tr>
                                            <td class="fw-semibold"><?= htmlspecialchars($estado) ?></td>
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
                                        $porcentaje = $estadisticas['total_asignados'] > 0 
                                            ? round(($cantidad / $estadisticas['total_asignados']) * 100, 1) 
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

            <!-- Distribución por Tipo de Delito -->
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0"><i class="bi bi-file-text"></i> Top 10 Tipos de Delito</h6>
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
                                        $porcentaje = round(($cantidad / $estadisticas['total_asignados']) * 100, 1);
                                        $contador++;
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($tipo) ?></td>
                                            <td class="text-center"><span class="badge bg-primary"><?= $cantidad ?></span></td>
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
                                        $porcentaje = round(($cantidad / $estadisticas['total_asignados']) * 100, 1);
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($zona_nombre) ?></td>
                                            <td class="text-center"><span class="badge bg-info"><?= $cantidad ?></span></td>
                                            <td>
                                                <div class="progress" style="height: 18px;">
                                                    <div class="progress-bar bg-info" style="width: <?= $porcentaje ?>%">
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
            <i class="bi bi-info-circle"></i> Selecciona los filtros y haz clic en <strong>"Generar Estadísticas"</strong> para ver tus métricas de rendimiento.
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

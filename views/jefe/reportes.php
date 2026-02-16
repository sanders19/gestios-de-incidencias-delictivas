<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/layout.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/css/dashboard_mesa.css">

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title">
                <h1 class="page-title">
                    <i class="bi bi-file-earmark-bar-graph text-success"></i> Generar Reportes
                </h1>
                <p class="page-subtitle">
                    <i class="bi bi-info-circle"></i> Genera reportes estadísticos en formato PDF
                </p>
            </div>
            <div class="header-actions">
                <a href="<?= BASE_URL ?>/jefe/dashboard" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        <hr class="divider">
    </div>

    <!-- Formulario de generación -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Nuevo Reporte</h5>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar-range"></i> Periodo
                    </label>
                    <select name="periodo" class="form-select" required>
                        <option value="7 días">Últimos 7 días</option>
                        <option value="Mes" selected>Mes actual</option>
                        <option value="Trimestre">Trimestre</option>
                        <option value="Año">Año</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-file-text"></i> Tipo de delito
                    </label>
                    <select name="tipo_delito" class="form-select">
                        <option value="">Todos</option>
                        <?php foreach ($tipos as $t): ?>
                            <option value="<?= htmlspecialchars($t['tipo_delito']) ?>">
                                <?= htmlspecialchars($t['tipo_delito']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-geo-alt"></i> Zona
                    </label>
                    <select name="id_zona" class="form-select">
                        <option value="">Todas</option>
                        <?php foreach ($zonas as $z): ?>
                            <option value="<?= $z['id_zona'] ?>">
                                <?= htmlspecialchars($z['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-person-badge"></i> Registrado por (Mesa)
                    </label>
                    <select name="id_registrado_por" class="form-select">
                        <option value="">Todos</option>
                        <?php foreach ($mesaUsuarios as $u): ?>
                            <option value="<?= htmlspecialchars($u['id_usuario']) ?>">
                                <?= htmlspecialchars($u['nombre_completo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-person-check"></i> Asignado a (SEINCRI)
                    </label>
                    <select name="id_asignado_a" class="form-select">
                        <option value="">Todos</option>
                        <?php foreach ($seincriUsuarios as $u): ?>
                            <option value="<?= htmlspecialchars($u['id_usuario']) ?>">
                                <?= htmlspecialchars($u['nombre_completo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-success px-4 py-2">
                        <i class="bi bi-file-pdf"></i> Generar y Exportar PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Historial de reportes generados -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Reportes Generados</h5>
        </div>
        <div class="card-body p-0">
            <?php
            // Obtener reportes desde la BD
            require_once __DIR__ . '/../../models/Database.php';
            $pdo = Database::getInstance()->getConnection();
            $stmt = $pdo->query("
                SELECT r.*, u.nombre_completo as generado_por_nombre
                FROM Reportes r
                LEFT JOIN Usuarios u ON r.generado_por = u.id_usuario
                ORDER BY r.generado_en DESC
                LIMIT 20
            ");
            $reportes = $stmt->fetchAll();
            ?>
            
            <?php if (!empty($reportes)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-head">
                            <tr>
                                <th class="table-th"><i class="bi bi-hash"></i> ID</th>
                                <th class="table-th"><i class="bi bi-calendar"></i> Periodo</th>
                                <th class="table-th"><i class="bi bi-file-text"></i> Tipo Delito</th>
                                <th class="table-th"><i class="bi bi-person"></i> Generado Por</th>
                                <th class="table-th"><i class="bi bi-clock"></i> Fecha</th>
                                <th class="table-th text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reportes as $r): ?>
                                <tr>
                                    <td class="px-4">
                                        <span class="badge bg-light text-dark border">#<?= $r['id_reporte'] ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($r['periodo']) ?></td>
                                    <td>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($r['tipo_delito'] ?: 'Todos') ?>
                                        </small>
                                    </td>
                                    <td>
                                        <small><?= htmlspecialchars($r['generado_por_nombre'] ?? 'N/A') ?></small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($r['generado_en'])) ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <?php if (!empty($r['ruta_exportacion']) && file_exists(__DIR__ . '/../../public/' . $r['ruta_exportacion'])): ?>
                                                <a href="<?= BASE_URL ?>/<?= htmlspecialchars($r['ruta_exportacion']) ?>"
                                                   target="_blank"
                                                   class="btn btn-sm btn-danger"
                                                   title="Ver PDF">
                                                    <i class="bi bi-file-pdf"></i> Ver PDF
                                                </a>
                                            <?php else: ?>
                                                <span class="badge bg-secondary me-2">No disponible</span>
                                            <?php endif; ?>
                                            
                                            <a href="<?= BASE_URL ?>/jefe/reportes/eliminar/<?= $r['id_reporte'] ?>"
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('¿Estás seguro de eliminar este reporte?\n\nID: #<?= $r['id_reporte'] ?>\nPeriodo: <?= htmlspecialchars($r['periodo']) ?>\n\nSe eliminará el archivo PDF y el registro de la base de datos.');"
                                               title="Eliminar reporte">
                                                <i class="bi bi-trash"></i>
                                            </a>
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
                    <p class="empty-text">No hay reportes generados</p>
                    <small class="text-muted">Genera tu primer reporte usando el formulario de arriba</small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

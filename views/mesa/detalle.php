<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/layout.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/css/dashboard_mesa.css">

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title">
                <h1 class="page-title">
                    <i class="bi bi-file-text text-primary"></i> Detalle de Incidencia
                </h1>
                <p class="page-subtitle">
                    <span class="badge bg-light text-dark border">#<?= htmlspecialchars($incidencia['id_incidencia']) ?></span>
                </p>
            </div>
            <div class="header-actions">
                <?php if ($incidencia['estado'] === 'Pendiente'): ?>
                    <a href="<?= BASE_URL ?>/mesa/editar/<?= $incidencia['id_incidencia'] ?>" class="btn btn-success">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/mesa/dashboard" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        <hr class="divider">
    </div>

    <div class="row g-4">
        <!-- Columna Izquierda -->
        <div class="col-lg-8">
            <!-- Información General -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información General</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Tipo de Delito</label>
                            <p class="fw-semibold mb-0"><?= htmlspecialchars($incidencia['tipo_delito']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Clasificación</label>
                            <p class="fw-semibold mb-0"><?= htmlspecialchars($incidencia['clasificacion_delito']) ?></p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">Descripción</label>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($incidencia['descripcion'])) ?></p>
                        </div>
                        <div class="col-md-8">
                            <label class="text-muted small">Dirección</label>
                            <p class="mb-0"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($incidencia['direccion_incidencia']) ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Zona</label>
                            <p class="mb-0"><?= htmlspecialchars($incidencia['zona_nombre'] ?? 'Sin zona') ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Estado</label>
                            <p class="mb-0">
                                <?php
                                $badgeClass = match($incidencia['estado']) {
                                    'Pendiente' => 'bg-warning text-dark',
                                    'Recibido' => 'bg-info',
                                    'Investigando' => 'bg-primary',
                                    'Resuelto' => 'bg-success',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($incidencia['estado']) ?></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Prioridad</label>
                            <p class="mb-0">
                                <?php
                                $prioridadClass = match($incidencia['prioridad']) {
                                    'Alta' => 'bg-danger',
                                    'Media' => 'bg-warning text-dark',
                                    'Baja' => 'bg-secondary',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?= $prioridadClass ?>"><?= htmlspecialchars($incidencia['prioridad']) ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mapa (si hay coordenadas) -->
            <?php if (!empty($incidencia['latitud']) && !empty($incidencia['longitud'])): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-map"></i> Ubicación Geográfica</h5>
                </div>
                <div class="card-body p-0">
                    <div id="mapa-detalle" style="height: 350px;"></div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <small class="text-muted">Latitud</small>
                            <p class="mb-0 fw-semibold"><?= htmlspecialchars($incidencia['latitud']) ?></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Longitud</small>
                            <p class="mb-0 fw-semibold"><?= htmlspecialchars($incidencia['longitud']) ?></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Confianza</small>
                            <p class="mb-0">
                                <?php
                                $confBadge = match($incidencia['geo_confidence']) {
                                    'exact' => 'bg-success',
                                    'close' => 'bg-warning text-dark',
                                    'approximate' => 'bg-secondary',
                                    default => 'bg-secondary'
                                };
                                $confText = match($incidencia['geo_confidence']) {
                                    'exact' => 'Exacta',
                                    'close' => 'Cercana',
                                    'approximate' => 'Aproximada',
                                    default => 'N/A'
                                };
                                ?>
                                <span class="badge <?= $confBadge ?>"><?= $confText ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Evidencias -->
            <?php if (!empty($evidencias)): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-camera"></i> Evidencias (<?= count($evidencias) ?>)</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php foreach ($evidencias as $ev): ?>
                            <div class="col-md-4">
                                <div class="card">
                                    <?php if ($ev['tipo_archivo'] === 'foto'): ?>
                                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($ev['ruta_archivo']) ?>" 
                                             class="card-img-top" alt="Evidencia"
                                             style="height: 150px; object-fit: cover; cursor: pointer;"
                                             onclick="window.open('<?= BASE_URL ?>/<?= htmlspecialchars($ev['ruta_archivo']) ?>', '_blank')">
                                    <?php elseif ($ev['tipo_archivo'] === 'video'): ?>
                                        <video class="card-img-top" style="height: 150px; object-fit: cover;" controls>
                                            <source src="<?= BASE_URL ?>/<?= htmlspecialchars($ev['ruta_archivo']) ?>">
                                        </video>
                                    <?php else: ?>
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                            <i class="bi bi-volume-up fs-1 text-muted"></i>
                                        </div>
                                        <audio controls class="w-100">
                                            <source src="<?= BASE_URL ?>/<?= htmlspecialchars($ev['ruta_archivo']) ?>">
                                        </audio>
                                    <?php endif; ?>
                                    <div class="card-body p-2">
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> <?= date('d/m/Y H:i', strtotime($ev['subido_en'])) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Columna Derecha -->
        <div class="col-lg-4">
            <!-- Denunciante -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-person"></i> Denunciante</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong><?= htmlspecialchars($incidencia['denunciante_nombre']) ?></strong></p>
                    <small class="text-muted">DNI: <?= htmlspecialchars($incidencia['denunciante_dni'] ?? 'No registrado') ?></small>
                </div>
            </div>

            <!-- Agredido -->
            <?php if ($incidencia['tipo_agredido'] === 'otra_persona' && !empty($incidencia['agredido_nombre'])): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="bi bi-person-x"></i> Agredido</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0"><strong><?= htmlspecialchars($incidencia['agredido_nombre']) ?></strong></p>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> El denunciante es la víctima
            </div>
            <?php endif; ?>

            <!-- Agresor -->
            <?php if (!empty($incidencia['agresor_nombre'])): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-person-slash"></i> Agresor</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0"><strong><?= htmlspecialchars($incidencia['agresor_nombre']) ?></strong></p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Metadata -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-clock-history"></i> Información del Registro</h6>
                </div>
                <div class="card-body">
                    <small class="d-block mb-2">
                        <i class="bi bi-calendar-plus"></i> Registrado: 
                        <strong><?= date('d/m/Y H:i', strtotime($incidencia['fecha_registro'])) ?></strong>
                    </small>
                    <?php if ($incidencia['asignado_a']): ?>
                        <small class="d-block">
                            <i class="bi bi-person-check"></i> Asignado a: 
                            <strong><?= htmlspecialchars($incidencia['asignado_a']) ?></strong>
                        </small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script del Mapa -->
<?php if (!empty($incidencia['latitud']) && !empty($incidencia['longitud'])): ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lat = <?= $incidencia['latitud'] ?>;
    const lng = <?= $incidencia['longitud'] ?>;
    
    const mapa = L.map('mapa-detalle').setView([lat, lng], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(mapa);
    
    L.marker([lat, lng]).addTo(mapa)
        .bindPopup('<strong>Ubicación de la incidencia</strong>')
        .openPopup();
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

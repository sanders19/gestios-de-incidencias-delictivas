<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/layout.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/css/dashboard_mesa.css">

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title">
                <h1 class="page-title">
                    <i class="bi bi-file-text text-primary"></i> Detalle del Caso
                </h1>
                <p class="page-subtitle">
                    <span class="badge bg-light text-dark border">#<?= htmlspecialchars($incidencia['id_incidencia']) ?></span>
                </p>
            </div>
            <div class="header-actions">
                <a href="<?= BASE_URL ?>/seincri/atencion" class="btn btn-outline-secondary">
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
                        <div class="col-12">
                            <label class="text-muted small">Dirección</label>
                            <p class="mb-0"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($incidencia['direccion_incidencia']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mapa (si hay coordenadas) -->
            <?php if (!empty($incidencia['latitud']) && !empty($incidencia['longitud'])): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-map"></i> Ubicación</h5>
                </div>
                <div class="card-body p-0">
                    <div id="mapa-detalle" style="height: 350px;"></div>
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

            <!-- Historial de Estados -->
            <?php if (!empty($historial)): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Historial de Cambios</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($historial as $h): ?>
                            <div class="d-flex mb-3">
                                <div class="me-3">
                                    <i class="bi bi-arrow-right-circle-fill text-primary fs-4"></i>
                                </div>
                                <div>
                                    <p class="mb-1">
                                        <span class="badge bg-secondary"><?= htmlspecialchars($h['estado_anterior'] ?? 'Inicial') ?></span>
                                        <i class="bi bi-arrow-right mx-2"></i>
                                        <span class="badge bg-success"><?= htmlspecialchars($h['estado_nuevo']) ?></span>
                                    </p>
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i> <?= htmlspecialchars($h['cambiado_por_nombre'] ?? 'Sistema') ?> - 
                                        <i class="bi bi-clock"></i> <?= date('d/m/Y H:i', strtotime($h['fecha_cambio'])) ?>
                                    </small>
                                    <?php if (!empty($h['notas'])): ?>
                                        <p class="mb-0 mt-1"><em>"<?= htmlspecialchars($h['notas']) ?>"</em></p>
                                    <?php endif; ?>
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
            <!-- Actualizar Estado -->
            <?php if ($incidencia['estado'] !== 'Resuelto'): ?>
            <div class="card border-0 shadow-sm mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-pencil-square"></i> Actualizar Estado</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= BASE_URL ?>/seincri/atencion/actualizar/<?= $incidencia['id_incidencia'] ?>">
                        <div class="mb-3">
                            <label class="form-label">Nuevo Estado</label>
                            <select name="estado_nuevo" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                <?php if ($incidencia['estado'] === 'Recibido'): ?>
                                    <option value="Investigando">🔍 Investigando</option>
                                    <option value="Resuelto">✅ Resuelto</option>
                                <?php elseif ($incidencia['estado'] === 'Investigando'): ?>
                                    <option value="Resuelto">✅ Resuelto</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notas (opcional)</label>
                            <textarea name="notas" class="form-control" rows="3" placeholder="Describe las acciones tomadas..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Actualizar Estado
                        </button>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> Este caso ya está <strong>Resuelto</strong>
            </div>
            <?php endif; ?>

            <!-- Personas Involucradas -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-person"></i> Denunciante</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong><?= htmlspecialchars($incidencia['denunciante_nombre']) ?></strong></p>
                    <small class="text-muted">DNI: <?= htmlspecialchars($incidencia['denunciante_dni'] ?? 'No registrado') ?></small>
                </div>
            </div>

            <?php if ($incidencia['tipo_agredido'] === 'otra_persona' && !empty($incidencia['agredido_nombre'])): ?>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="bi bi-person-x"></i> Agredido</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0"><strong><?= htmlspecialchars($incidencia['agredido_nombre']) ?></strong></p>
                </div>
            </div>
            <?php endif; ?>

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
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Información del Caso</h6>
                </div>
                <div class="card-body">
                    <small class="d-block mb-2">
                        <strong>Estado:</strong> 
                        <?php
                        $estadoClass = match($incidencia['estado']) {
                            'Recibido' => 'bg-info',
                            'Investigando' => 'bg-primary',
                            'Resuelto' => 'bg-success',
                            default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $estadoClass ?>"><?= htmlspecialchars($incidencia['estado']) ?></span>
                    </small>
                    <small class="d-block mb-2">
                        <strong>Prioridad:</strong> 
                        <?php
                        $prioridadClass = match($incidencia['prioridad']) {
                            'Alta' => 'bg-danger',
                            'Media' => 'bg-warning text-dark',
                            'Baja' => 'bg-secondary',
                            default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $prioridadClass ?>"><?= htmlspecialchars($incidencia['prioridad']) ?></span>
                    </small>
                    <small class="d-block mb-2">
                        <i class="bi bi-calendar-plus"></i> Registrado: 
                        <strong><?= date('d/m/Y H:i', strtotime($incidencia['fecha_registro'])) ?></strong>
                    </small>
                    <small class="d-block">
                        <i class="bi bi-person-badge"></i> Por: 
                        <strong><?= htmlspecialchars($incidencia['registrado_por']) ?></strong>
                    </small>
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

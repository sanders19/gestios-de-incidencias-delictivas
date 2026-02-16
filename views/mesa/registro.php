<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h2 class="text-success mb-4">Registrar Nueva Incidencia</h2>

    <form method="POST" enctype="multipart/form-data" class="card shadow-sm p-4 bg-light border-success">
        <h4 class="text-success">Denunciante</h4>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Nombre completo</label>
                <input type="text" name="denunciante_nombre" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Sexo</label>
                <select name="denunciante_sexo" class="form-select" required>
                    <option>Masculino</option>
                    <option>Femenino</option>
                    <option>Otro</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">DNI</label>
                <input type="text" name="denunciante_dni" class="form-control">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-8">
                <label class="form-label">Dirección</label>
                <input type="text" name="denunciante_direccion" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Teléfono</label>
                <input type="text" name="denunciante_telefono" class="form-control">
            </div>
        </div>

        <h4 class="text-success mt-4">Agredido</h4>
        <div class="mb-3">
            <div class="form-check form-check-inline">
                <input type="radio" id="soyYo" name="tipo_agredido" value="soy_yo" class="form-check-input" checked>
                <label for="soyYo" class="form-check-label">Soy yo</label>
            </div>
            <div class="form-check form-check-inline">
                <input type="radio" id="otraPersona" name="tipo_agredido" value="otra_persona" class="form-check-input">
                <label for="otraPersona" class="form-check-label">Otra persona</label>
            </div>
        </div>

        <div id="datos-agredido" class="p-3 border rounded bg-white mb-4" style="display:none;">
            <h5 class="text-secondary">Datos de la otra persona</h5>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="agredido_nombre" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sexo</label>
                    <select name="agredido_sexo" class="form-select">
                        <option>Masculino</option>
                        <option>Femenino</option>
                        <option>Otro</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">DNI</label>
                    <input type="text" name="agredido_dni" class="form-control">
                </div>
            </div>
        </div>

        <h4 class="text-success mt-4">Agresor (opcional)</h4>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Nombre completo</label>
                <input type="text" name="agresor_nombre" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sexo</label>
                <select name="agresor_sexo" class="form-select">
                    <option>Masculino</option>
                    <option>Femenino</option>
                    <option>Otro</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">DNI</label>
                <input type="text" name="agresor_dni" class="form-control">
            </div>
        </div>

        <h4 class="text-success mt-4">Detalles de la Incidencia</h4>
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Tipo de delito</label>
                <select name="tipo_delito" id="tipo-delito" class="form-select" required>
                    <option value="">Seleccionar tipo</option>
                    <?php foreach ($tiposUnicos as $tipo): ?>
                        <option value="<?= htmlspecialchars($tipo) ?>"><?= htmlspecialchars($tipo) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Clasificación</label>
                <select name="clasificacion_delito" id="clasificacion-delito" class="form-select" required>
                    <option value="">Seleccionar clasificación</option>
                    <?php foreach ($delitosClasificaciones as $dc): ?>
                        <option value="<?= htmlspecialchars($dc['clasificacion']) ?>"
                                data-tipo="<?= htmlspecialchars($dc['tipo_delito']) ?>"
                                style="display:none;">
                            <?= htmlspecialchars($dc['clasificacion']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Zona</label>
                <select name="id_zona" id="id-zona" class="form-select" required>
                    <option value="">Seleccionar zona</option>
                    <?php foreach ($zonas as $z): ?>
                        <option value="<?= $z['id_zona'] ?>"
                                data-centroid-lat="<?= htmlspecialchars($z['centroid_lat'] ?? '') ?>"
                                data-centroid-lng="<?= htmlspecialchars($z['centroid_lng'] ?? '') ?>">
                            <?= htmlspecialchars($z['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" rows="3" class="form-control" required></textarea>
        </div>

        <div class="row mb-3">
            <div class="col-md-8">
                <label class="form-label">Dirección de la incidencia</label>
                <input type="text" name="direccion_incidencia" id="direccion-incidencia" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Geolocalización (opcional)</label>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" id="btn-ubicar-mapa" class="btn btn-outline-primary" disabled>
                        📍 Ubicar en mapa
                    </button>
                    <span id="geo-badge" class="badge bg-secondary">No marcada</span>
                </div>
                <small class="text-muted">Primero selecciona una zona</small>
            </div>
        </div>

        <input type="hidden" name="latitud" id="latitud" value="">
        <input type="hidden" name="longitud" id="longitud" value="">
        <input type="hidden" name="geo_confidence" id="geo-confidence" value="">

        <div class="row mb-4">
            <div class="col-md-4">
                <label class="form-label">Prioridad</label>
                <select name="prioridad" class="form-select">
                    <option>Baja</option>
                    <option selected>Media</option>
                    <option>Alta</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">Evidencias (máx. 5 archivos)</label>
                <input type="file" name="evidencias[]" multiple accept="image/*,video/*,audio/*" class="form-control">
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success px-4">Registrar Incidencia</button>
            <a href="<?= BASE_URL ?>/mesa/dashboard" class="btn btn-outline-secondary ms-2">← Volver</a>
        </div>
    </form>
</div>

<div class="modal fade" id="modal-mapa" tabindex="-1" aria-labelledby="modalMapaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalMapaLabel">📍 Ubicar incidencia en el mapa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3">
                    <strong>Instrucciones:</strong> Haz clic en el mapa para marcar la ubicación exacta de la incidencia, o arrastra el marcador.
                </div>
                <div id="mapa-leaflet" style="height: 450px; border-radius: 8px;"></div>
                <div class="mt-3">
                    <p class="mb-1"><strong>Coordenadas seleccionadas:</strong></p>
                    <p class="text-muted mb-0">
                        Latitud: <span id="display-lat">--</span> | 
                        Longitud: <span id="display-lng">--</span> | 
                        Confianza: <span id="display-confidence" class="badge bg-secondary">--</span>
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btn-confirmar-ubicacion" class="btn btn-primary" data-bs-dismiss="modal">✓ Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="tipo_agredido"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.getElementById('datos-agredido').style.display = radio.value === 'otra_persona' ? 'block' : 'none';
    });
});

const tipoSelect = document.getElementById('tipo-delito');
const clasifSelect = document.getElementById('clasificacion-delito');
tipoSelect.addEventListener('change', () => {
    const tipo = tipoSelect.value;
    clasifSelect.querySelectorAll('option').forEach(opt => {
        opt.style.display = opt.dataset.tipo === tipo ? 'block' : 'none';
    });
    clasifSelect.value = '';
});

const zonaSelect = document.getElementById('id-zona');
const btnUbicarMapa = document.getElementById('btn-ubicar-mapa');
zonaSelect.addEventListener('change', () => {
    btnUbicarMapa.disabled = !zonaSelect.value;
});

// CÓDIGO DEL MAPA INLINE (sin archivo externo)
let mapa = null, marcador = null, leafletCargado = false, modalMapa = null;
const DEFAULT_CENTER = [-12.7872800, -74.9726600], DEFAULT_ZOOM = 15;

document.addEventListener('DOMContentLoaded', function() {
    const latInput = document.getElementById('latitud');
    const lngInput = document.getElementById('longitud');
    const confInput = document.getElementById('geo-confidence');
    const geoBadge = document.getElementById('geo-badge');
    const displayLat = document.getElementById('display-lat');
    const displayLng = document.getElementById('display-lng');
    const displayConf = document.getElementById('display-confidence');
    
    if(typeof bootstrap !== 'undefined') {
        modalMapa = new bootstrap.Modal(document.getElementById('modal-mapa'));
    }
    
    function cargarLeaflet() {
        return new Promise((resolve, reject) => {
            if(leafletCargado) { resolve(); return; }
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            document.head.appendChild(link);
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            script.onload = () => { leafletCargado = true; resolve(); };
            script.onerror = () => reject(new Error('Error Leaflet'));
            document.head.appendChild(script);
        });
    }
    
    function obtenerCentroidZona() {
        const opt = zonaSelect.options[zonaSelect.selectedIndex];
        const lat = parseFloat(opt.dataset.centroidLat);
        const lng = parseFloat(opt.dataset.centroidLng);
        return (isNaN(lat) || isNaN(lng)) ? DEFAULT_CENTER : [lat, lng];
    }
    
    function calcularDistancia(lat1, lon1, lat2, lon2) {
        const R = 6371e3, φ1 = lat1*Math.PI/180, φ2 = lat2*Math.PI/180;
        const Δφ = (lat2-lat1)*Math.PI/180, Δλ = (lon2-lon1)*Math.PI/180;
        const a = Math.sin(Δφ/2)*Math.sin(Δφ/2) + Math.cos(φ1)*Math.cos(φ2)*Math.sin(Δλ/2)*Math.sin(Δλ/2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }
    
    function calcularConfianza(lat, lng) {
        const c = obtenerCentroidZona();
        const d = calcularDistancia(lat, lng, c[0], c[1]);
        return d < 100 ? 'exact' : d < 500 ? 'close' : 'approximate';
    }
    
    function actualizarCoordenadas(lat, lng) {
        const latR = lat.toFixed(7), lngR = lng.toFixed(7);
        const conf = calcularConfianza(lat, lng);
        latInput.value = latR; lngInput.value = lngR; confInput.value = conf;
        displayLat.textContent = latR; displayLng.textContent = lngR;
        const txt = {exact:'Exacta', close:'Cercana', approximate:'Aproximada'};
        const col = {exact:'bg-success', close:'bg-warning', approximate:'bg-secondary'};
        displayConf.textContent = txt[conf];
        displayConf.className = `badge ${col[conf]}`;
        geoBadge.textContent = '✓ Marcada';
        geoBadge.className = 'badge bg-success';
    }
    
    btnUbicarMapa.addEventListener('click', async function(e) {
        e.preventDefault();
        await cargarLeaflet();
        modalMapa.show();
        setTimeout(() => {
            if(!mapa) {
                const c = obtenerCentroidZona();
                mapa = L.map('mapa-leaflet').setView(c, DEFAULT_ZOOM);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom:19}).addTo(mapa);
                marcador = L.marker(c, {draggable:true}).addTo(mapa);
                marcador.on('dragend', e => actualizarCoordenadas(e.target.getLatLng().lat, e.target.getLatLng().lng));
                mapa.on('click', e => { marcador.setLatLng(e.latlng); actualizarCoordenadas(e.latlng.lat, e.latlng.lng); });
            } else {
                const c = obtenerCentroidZona();
                mapa.setView(c, DEFAULT_ZOOM);
                marcador.setLatLng(c);
            }
            mapa.invalidateSize();
        }, 300);
    });
    
    zonaSelect.addEventListener('change', () => {
        latInput.value = ''; lngInput.value = ''; confInput.value = '';
        geoBadge.textContent = 'No marcada'; geoBadge.className = 'badge bg-secondary';
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

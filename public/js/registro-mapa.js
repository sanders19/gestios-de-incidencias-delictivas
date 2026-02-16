/**
 * registro-mapa.js
 * Georreferenciación con Leaflet (lazy-load) para registro de incidencias
 */

let mapa = null;
let marcador = null;
let leafletCargado = false;

// Referencias a elementos del DOM
const btnUbicarMapa = document.getElementById('btn-ubicar-mapa');
const modalMapa = new bootstrap.Modal(document.getElementById('modal-mapa'));
const zonaSelect = document.getElementById('id-zona');
const latInput = document.getElementById('latitud');
const lngInput = document.getElementById('longitud');
const confInput = document.getElementById('geo-confidence');
const geoBadge = document.getElementById('geo-badge');
const displayLat = document.getElementById('display-lat');
const displayLng = document.getElementById('display-lng');
const displayConf = document.getElementById('display-confidence');

// Coordenadas por defecto (centro de Huancavelica)
const DEFAULT_CENTER = [-12.7872800, -74.9726600];
const DEFAULT_ZOOM = 15;

/**
 * Carga lazy de Leaflet CSS y JS
 */
function cargarLeaflet() {
    return new Promise((resolve, reject) => {
        if (leafletCargado) {
            resolve();
            return;
        }

        // Cargar CSS
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
        link.integrity = 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
        link.crossOrigin = '';
        document.head.appendChild(link);

        // Cargar JS
        const script = document.createElement('script');
        script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
        script.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
        script.crossOrigin = '';
        script.onload = () => {
            leafletCargado = true;
            resolve();
        };
        script.onerror = () => reject(new Error('Error al cargar Leaflet'));
        document.head.appendChild(script);
    });
}

/**
 * Inicializa el mapa Leaflet
 */
function inicializarMapa() {
    const centroid = obtenerCentroidZona();
    
    mapa = L.map('mapa-leaflet').setView(centroid, DEFAULT_ZOOM);
    
    // Capa de mapa (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(mapa);

    // Crear marcador draggable
    marcador = L.marker(centroid, { draggable: true }).addTo(mapa);
    
    // Evento cuando se arrastra el marcador
    marcador.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        actualizarCoordenadas(pos.lat, pos.lng);
    });

    // Evento al hacer clic en el mapa
    mapa.on('click', function(e) {
        marcador.setLatLng(e.latlng);
        actualizarCoordenadas(e.latlng.lat, e.latlng.lng);
    });
}

/**
 * Obtiene el centroid de la zona seleccionada
 */
function obtenerCentroidZona() {
    const opcionSeleccionada = zonaSelect.options[zonaSelect.selectedIndex];
    const lat = parseFloat(opcionSeleccionada.dataset.centroidLat);
    const lng = parseFloat(opcionSeleccionada.dataset.centroidLng);
    
    if (isNaN(lat) || isNaN(lng)) {
        return DEFAULT_CENTER;
    }
    return [lat, lng];
}

/**
 * Calcula la distancia en metros entre dos puntos (Haversine)
 */
function calcularDistancia(lat1, lon1, lat2, lon2) {
    const R = 6371e3; // Radio de la Tierra en metros
    const φ1 = lat1 * Math.PI / 180;
    const φ2 = lat2 * Math.PI / 180;
    const Δφ = (lat2 - lat1) * Math.PI / 180;
    const Δλ = (lon2 - lon1) * Math.PI / 180;

    const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
              Math.cos(φ1) * Math.cos(φ2) *
              Math.sin(Δλ/2) * Math.sin(Δλ/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

    return R * c; // Distancia en metros
}

/**
 * Calcula el nivel de confianza basado en distancia al centroid
 */
function calcularConfianza(latMarcador, lngMarcador) {
    const centroid = obtenerCentroidZona();
    const distancia = calcularDistancia(
        latMarcador, lngMarcador,
        centroid[0], centroid[1]
    );

    if (distancia < 100) return 'exact';      // Menos de 100m
    if (distancia < 500) return 'close';      // Menos de 500m
    return 'approximate';                      // Más de 500m
}

/**
 * Actualiza las coordenadas y la confianza
 */
function actualizarCoordenadas(lat, lng) {
    const latRedondeado = lat.toFixed(7);
    const lngRedondeado = lng.toFixed(7);
    const confianza = calcularConfianza(lat, lng);

    // Actualizar inputs ocultos
    latInput.value = latRedondeado;
    lngInput.value = lngRedondeado;
    confInput.value = confianza;

    // Actualizar displays en el modal
    displayLat.textContent = latRedondeado;
    displayLng.textContent = lngRedondeado;
    
    // Actualizar badge de confianza con color
    const confTexto = {
        'exact': 'Exacta',
        'close': 'Cercana',
        'approximate': 'Aproximada'
    };
    const confColor = {
        'exact': 'bg-success',
        'close': 'bg-warning',
        'approximate': 'bg-secondary'
    };
    displayConf.textContent = confTexto[confianza];
    displayConf.className = `badge ${confColor[confianza]}`;

    // Actualizar badge principal
    geoBadge.textContent = '✓ Marcada';
    geoBadge.className = 'badge bg-success';
}

/**
 * Evento al abrir el modal
 */
btnUbicarMapa.addEventListener('click', async function() {
    try {
        // Cargar Leaflet si no está cargado
        await cargarLeaflet();
        
        // Mostrar modal
        modalMapa.show();
        
        // Inicializar mapa después de que el modal esté visible
        setTimeout(() => {
            if (!mapa) {
                inicializarMapa();
            } else {
                // Re-centrar si el mapa ya existe
                const centroid = obtenerCentroidZona();
                mapa.setView(centroid, DEFAULT_ZOOM);
                marcador.setLatLng(centroid);
                
                // Si ya hay coordenadas guardadas, mostrarlas
                if (latInput.value && lngInput.value) {
                    const latGuardada = parseFloat(latInput.value);
                    const lngGuardada = parseFloat(lngInput.value);
                    mapa.setView([latGuardada, lngGuardada], DEFAULT_ZOOM);
                    marcador.setLatLng([latGuardada, lngGuardada]);
                    actualizarCoordenadas(latGuardada, lngGuardada);
                }
            }
            // Invalidar tamaño del mapa (fix de Bootstrap modal)
            mapa.invalidateSize();
        }, 300);
        
    } catch (error) {
        console.error('Error al cargar el mapa:', error);
        alert('Error al cargar el mapa. Por favor, intenta de nuevo.');
    }
});

/**
 * Resetear marcador si se cambia de zona
 */
zonaSelect.addEventListener('change', function() {
    if (mapa && marcador) {
        const centroid = obtenerCentroidZona();
        mapa.setView(centroid, DEFAULT_ZOOM);
        marcador.setLatLng(centroid);
        
        // Limpiar coordenadas guardadas
        latInput.value = '';
        lngInput.value = '';
        confInput.value = '';
        geoBadge.textContent = 'No marcada';
        geoBadge.className = 'badge bg-secondary';
    }
});

console.log('✓ registro-mapa.js cargado correctamente');

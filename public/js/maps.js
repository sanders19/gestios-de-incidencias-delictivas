// Integración con Google Maps (solo si usas coordenadas)
function initMap() {
    // Esta función se llama desde el HTML si usas Google Maps
    const mapContainer = document.getElementById('map');
    if (!mapContainer) return;

    const lat = parseFloat(mapContainer.dataset.lat) || -12.7825; // Huancavelica por defecto
    const lng = parseFloat(mapContainer.dataset.lng) || -74.9825;

    const map = new google.maps.Map(mapContainer, {
        zoom: 14,
        center: { lat, lng }
    });

    new google.maps.Marker({
        position: { lat, lng },
        map: map,
        title: 'Ubicación de la incidencia'
    });
}

// Cargar mapa solo si hay contenedor
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('map')) {
        // Asegúrate de tener el script de Google Maps en tu layout
        console.log('Mapa disponible. Asegúrate de incluir la API de Google Maps en el HTML.');
    }
});
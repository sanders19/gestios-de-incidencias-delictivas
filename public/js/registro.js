document.addEventListener('DOMContentLoaded', function() {
    // Toggle datos de agredido
    const radios = document.querySelectorAll('input[name="tipo_agredido"]');
    const datosAgredido = document.getElementById('datos-agredido');

    function toggleAgredido() {
        const seleccionado = document.querySelector('input[name="tipo_agredido"]:checked').value;
        datosAgredido.style.display = (seleccionado === 'otra_persona') ? 'block' : 'none';
    }

    radios.forEach(radio => {
        radio.addEventListener('change', toggleAgredido);
    });
    toggleAgredido();

    // Filtrar clasificaciones por tipo de delito
    function filtrarClasificaciones() {
        const tipoSeleccionado = document.getElementById('tipo-delito').value;
        const opciones = document.querySelectorAll('#clasificacion-delito option[data-tipo]');
        
        opciones.forEach(opcion => {
            if (!tipoSeleccionado || opcion.dataset.tipo === tipoSeleccionado) {
                opcion.style.display = 'block';
            } else {
                opcion.style.display = 'none';
            }
        });
        
        // Resetear selección de clasificación
        document.getElementById('clasificacion-delito').value = '';
    }

    const tipoDelitoSelect = document.getElementById('tipo-delito');
    if (tipoDelitoSelect) {
        tipoDelitoSelect.addEventListener('change', filtrarClasificaciones);
        filtrarClasificaciones(); // Inicializar
    }

    // Validación al enviar formulario
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const denunciante = document.querySelector('input[name="denunciante_nombre"]').value.trim();
            const tipoDelito = document.getElementById('tipo-delito').value;
            const clasificacion = document.getElementById('clasificacion-delito').value;
            const descripcion = document.querySelector('textarea[name="descripcion"]').value.trim();
            const direccion = document.querySelector('input[name="direccion_incidencia"]').value.trim();

            if (!denunciante || !tipoDelito || !clasificacion || !descripcion || !direccion) {
                e.preventDefault();
                alert('Por favor, completa todos los campos obligatorios.');
                return;
            }

            // Validar evidencias (máximo 5)
            const evidencias = document.querySelector('input[name="evidencias[]"]');
            if (evidencias && evidencias.files.length > 5) {
                e.preventDefault();
                alert('Máximo 5 archivos de evidencia permitidos.');
                return;
            }
        });
    }
});
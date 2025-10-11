document.addEventListener('DOMContentLoaded', function () {
    // Toggle datos de agredido
    const radios = document.querySelectorAll('input[name="tipo_agredido"]');
    const datosAgredido = document.getElementById('datos-agredido');

    function toggleAgredido() {
        const selected = document.querySelector('input[name="tipo_agredido"]:checked').value;
        datosAgredido.style.display = (selected === 'otra_persona') ? 'block' : 'none';
    }

    radios.forEach(radio => radio.addEventListener('change', toggleAgredido));
    toggleAgredido(); // Inicial

    // Validación al enviar
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            const denunciante = document.querySelector('input[name="denunciante_nombre"]').value.trim();
            const tipoDelito = document.querySelector('select[name="tipo_delito"]').value;
            const clasificacion = document.querySelector('select[name="clasificacion_delito"]').value;
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
// Mejorar experiencia de búsqueda (filtros dinámicos)
document.addEventListener('DOMContentLoaded', function () {
    const fechaDesde = document.querySelector('input[name="fecha_desde"]');
    const fechaHasta = document.querySelector('input[name="fecha_hasta"]');

    if (fechaDesde && fechaHasta) {
        // Limitar fecha hasta no menor que fecha desde
        fechaDesde.addEventListener('change', function () {
            if (fechaHasta.value && new Date(fechaHasta.value) < new Date(this.value)) {
                fechaHasta.value = this.value;
            }
        });

        fechaHasta.addEventListener('change', function () {
            if (fechaDesde.value && new Date(this.value) < new Date(fechaDesde.value)) {
                fechaDesde.value = this.value;
            }
        });
    }
});
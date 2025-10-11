// Interacción con filtros de reportes
document.addEventListener('DOMContentLoaded', function () {
    const periodoSelect = document.querySelector('select[name="periodo"]');
    const fechaDesde = document.querySelector('input[name="fecha_desde"]');
    const fechaHasta = document.querySelector('input[name="fecha_hasta"]');

    if (periodoSelect && fechaDesde && fechaHasta) {
        periodoSelect.addEventListener('change', function () {
            const hoy = new Date();
            let desde, hasta;

            switch (this.value) {
                case '7 días':
                    desde = new Date(hoy);
                    desde.setDate(hoy.getDate() - 7);
                    hasta = hoy;
                    break;
                case 'Mes':
                    desde = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
                    hasta = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);
                    break;
                case 'Trimestre':
                    const trimestre = Math.floor(hoy.getMonth() / 3);
                    desde = new Date(hoy.getFullYear(), trimestre * 3, 1);
                    hasta = new Date(hoy.getFullYear(), (trimestre + 1) * 3, 0);
                    break;
                case 'Año':
                    desde = new Date(hoy.getFullYear(), 0, 1);
                    hasta = new Date(hoy.getFullYear(), 11, 31);
                    break;
                default:
                    fechaDesde.value = '';
                    fechaHasta.value = '';
                    return;
            }

            fechaDesde.value = desde.toISOString().split('T')[0];
            fechaHasta.value = hasta.toISOString().split('T')[0];
        });
    }
});
// Funcionalidades globales: notificaciones, sidebar, etc.
document.addEventListener('DOMContentLoaded', function () {

    // Cerrar alertas automáticamente después de 5 segundos
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Toggle sidebar en móviles (si decides implementarlo después)
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });
    }

    // Confirmación en acciones críticas (ej: logout)
    const logoutLinks = document.querySelectorAll('a[href="/logout"]');
    logoutLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            if (!confirm('¿Seguro que deseas cerrar sesión?')) {
                e.preventDefault();
            }
        });
    });
});
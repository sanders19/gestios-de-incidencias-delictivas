/**
 * Scripts para el Header
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Auto-cerrar toasts después de 5 segundos
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        setTimeout(() => {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }, 5000);
    });

    // Cerrar navbar en móviles al hacer clic en un enlace
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                    toggle: false
                });
                bsCollapse.hide();
            }
        });
    });

    // Marcar notificaciones como leídas
    const notificationItems = document.querySelectorAll('#notificationsDropdown + .dropdown-menu .dropdown-item');
    notificationItems.forEach(item => {
        item.addEventListener('click', function() {
            this.classList.add('opacity-50');
        });
    });

    // Confirmar cierre de sesión
    const logoutLink = document.querySelector('a[href*="logout"]');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            if (!confirm('¿Está seguro que desea cerrar sesión?')) {
                e.preventDefault();
            }
        });
    }
});

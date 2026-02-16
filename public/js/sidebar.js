/**
 * Scripts para Sidebar Responsive
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Toggle sidebar en móviles
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebarMenu');
    
    if (sidebarToggle && sidebar) {
        // Crear backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'sidebar-backdrop';
        document.body.appendChild(backdrop);
        
        // Toggle sidebar
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            backdrop.classList.toggle('show');
        });
        
        // Cerrar al hacer clic en backdrop
        backdrop.addEventListener('click', function() {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
        });
        
        // Cerrar al hacer clic en un enlace (móviles)
        const navLinks = sidebar.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                }
            });
        });
    }
    
    // Marcar enlace activo
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
});

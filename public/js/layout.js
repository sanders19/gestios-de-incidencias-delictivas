/**
 * Scripts para Layout General
 * Header y Footer
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // RELOJ EN TIEMPO REAL
    // ============================================
    function updateTime() {
        const now = new Date();
        const timeElement = document.getElementById('currentTime');
        
        if (timeElement) {
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            timeElement.textContent = `${hours}:${minutes}`;
        }
    }
    
    // Actualizar cada minuto
    updateTime();
    setInterval(updateTime, 60000);
    
    // ============================================
    // AUTO-CERRAR ALERTAS
    // ============================================
    const alerts = document.querySelectorAll('.alert-flash');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000); // 5 segundos
    });
    
    // ============================================
    // CONFIRMAR SALIR
    // ============================================
    const logoutLinks = document.querySelectorAll('a[href*="logout"]');
    logoutLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('¿Está seguro que desea cerrar sesión?')) {
                e.preventDefault();
            }
        });
    });
    
    // ============================================
    // SMOOTH SCROLL
    // ============================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // ============================================
    // ANIMACIÓN DE ENTRADA
    // ============================================
    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
        mainContent.classList.add('fade-in');
    }
});

// Validación básica en login
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const usuario = document.querySelector('input[name="nombre_usuario"]').value.trim();
        const pass = document.querySelector('input[name="contrasena"]').value;

        if (!usuario || !pass) {
            e.preventDefault();
            alert('Por favor, completa todos los campos.');
        }
    });
});
<?php
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/ForcePasswordChangeMiddleware.php';

class AuthMiddleware {
    public static function check() {
        if (!Session::has('usuario')) {
            header('Location: /login');
            exit;
        }
        
        // ✅ NUEVA LÍNEA: Verificar si debe cambiar contraseña
        ForcePasswordChangeMiddleware::check();
    }
}

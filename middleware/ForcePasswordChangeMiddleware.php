<?php
require_once __DIR__ . '/../helpers/Session.php';

class ForcePasswordChangeMiddleware {
    public static function check() {
        // Si no hay sesión, redirigir al login
        if (!Session::has('usuario')) {
            header('Location: /login');
            exit;
        }

        $usuario = Session::get('usuario');
        
        // Si es primer inicio, solo permitir cambiar contraseña o logout
        if ($usuario['es_primer_inicio']) {
            $rutaActual = $_SERVER['REQUEST_URI'];
            
            // Rutas permitidas para usuarios con cambio de contraseña pendiente
            $rutasPermitidas = [
                '/cambiar-contrasena',
                '/logout'
            ];
            
            $permitido = false;
            foreach ($rutasPermitidas as $ruta) {
                if (strpos($rutaActual, $ruta) === 0) {
                    $permitido = true;
                    break;
                }
            }
            
            // Si intenta acceder a cualquier otra ruta, redirigir
            if (!$permitido) {
                header('Location: /cambiar-contrasena');
                exit;
            }
        }
    }
}

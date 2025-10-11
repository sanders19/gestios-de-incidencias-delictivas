<?php
require_once __DIR__ . '/../helpers/Session.php';

class RoleMiddleware {
    public static function check($handler) {
        $usuario = Session::get('usuario');
        if (!$usuario) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $rol = $usuario['rol'];
        $ruta = $_SERVER['REQUEST_URI'] ?? '/';

        // Extraer prefijo de la ruta para verificar rol
        if (strpos($ruta, '/mesa/') === 0 && $rol !== 'mesa') {
            self::denyAccess();
        } elseif (strpos($ruta, '/seincri/') === 0 && $rol !== 'seincri') {
            self::denyAccess();
        } elseif (strpos($ruta, '/jefe/') === 0 && $rol !== 'jefe') {
            self::denyAccess();
        }

        // También puedes usar el nombre del controlador si prefieres
        // Ejemplo: si $handler contiene 'Mesa', verificar rol = 'mesa'
        // Pero la verificación por ruta es más simple y segura aquí.
    }

    private static function denyAccess() {
        http_response_code(403);
        echo "<h1>Acceso denegado</h1><p>No tienes permisos para acceder a esta sección.</p>";
        exit;
    }
}
?>
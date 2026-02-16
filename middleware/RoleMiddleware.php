<?php


class RoleMiddleware {
    public static function check($handler) {
        $usuario = Session::get('usuario');
        if (!$usuario) {
            // ✅ Usar ruta relativa en lugar de BASE_URL
            header('Location: /login');
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
    }

    private static function denyAccess() {
        http_response_code(403);
        echo "<h1>Acceso denegado</h1><p>No tienes permisos para acceder a esta sección.</p>";
        exit;
    }
}

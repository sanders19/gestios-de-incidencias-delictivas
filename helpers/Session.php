<?php

class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            error_log("LOG Session.php: Iniciando sesión");
            session_start();
        }
    }

    public static function set($key, $value) {
        self::start();
        error_log("LOG Session.php: Guardando en sesión - Clave: $key, Valor: " . (is_array($value) ? json_encode($value) : $value));
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        self::start();
        $valor = $_SESSION[$key] ?? $default;
        error_log("LOG Session.php: Obteniendo de sesión - Clave: $key, Valor: " . (is_array($valor) ? json_encode($valor) : $valor));
        return $valor;
    }

    public static function has($key) {
        self::start();
        $existe = isset($_SESSION[$key]);
        error_log("LOG Session.php: Verificando existencia en sesión - Clave: $key, Existe: " . ($existe ? 'Sí' : 'No'));
        return $existe;
    }

    public static function destroy() {
        self::start();
        error_log("LOG Session.php: Destruyendo sesión");
        $_SESSION = [];
        session_destroy();
    }

    public static function setFlash($type, $message) {
        self::start();
        error_log("LOG Session.php: Guardando flash message - Tipo: $type, Mensaje: $message");
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    public static function getFlash() {
        self::start();
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            error_log("LOG Session.php: Obteniendo flash message - Tipo: {$flash['type']}, Mensaje: {$flash['message']}");
            return $flash;
        }
        return null;
    }
}

Session::start();
?>
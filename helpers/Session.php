<?php

class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            error_log("LOG Session.php: Iniciando sesión");
            session_start();
            error_log("LOG Session.php: Sesión iniciada - ID: " . session_id());
        }
    }

    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
        error_log("LOG Session.php: Set - Clave: $key");
    }

    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function destroy() {
        self::start();
        error_log("LOG Session.php: Destruyendo sesión");
        $_SESSION = [];
        session_destroy();
    }

    public static function setFlash($type, $message) {
        self::start();
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
        error_log("LOG Session.php: Flash guardado - Tipo: $type");
    }

    public static function getFlash() {
        self::start();
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            error_log("LOG Session.php: Flash obtenido - Tipo: {$flash['type']}");
            return $flash;
        }
        return null;
    }
}

// ❌ ELIMINADO: Session::start(); 
// La sesión se iniciará cuando se llame explícitamente

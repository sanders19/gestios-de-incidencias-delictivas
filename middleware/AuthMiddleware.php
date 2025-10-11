<?php
require_once __DIR__ . '/../helpers/Session.php';

class AuthMiddleware {
    public static function check() {
        if (!Session::has('usuario')) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
}
?>
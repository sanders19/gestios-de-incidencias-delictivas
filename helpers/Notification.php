<?php

class Notification {
    public static function success($message) {
        Session::setFlash('success', $message);
    }

    public static function error($message) {
        Session::setFlash('error', $message);
    }

    public static function warning($message) {
        Session::setFlash('warning', $message);
    }

    public static function info($message) {
        Session::setFlash('info', $message);
    }
}
?>
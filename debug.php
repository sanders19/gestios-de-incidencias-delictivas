<?php
require_once __DIR__ . '/helpers/Session.php';
echo "<h2>Debug de Sesión</h2>";
echo "<p>BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'No definido') . "</p>";
if (Session::has('usuario')) {
    echo "<p>✅ Usuario logueado: " . htmlspecialchars(Session::get('usuario')['nombre_usuario']) . "</p>";
    echo "<pre>";
    print_r(Session::get('usuario'));
    echo "</pre>";
} else {
    echo "<p>❌ No hay sesión activa.</p>";
}
?>
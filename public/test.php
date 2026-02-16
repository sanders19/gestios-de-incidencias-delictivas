<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Test</title></head><body>";
echo "<h1>🔍 TEST SIMPLE</h1><hr>";

echo "<h2>✅ PHP está funcionando</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Hora actual: " . date('Y-m-d H:i:s') . "<br>";

echo "<hr><h2>📁 Verificación de archivos</h2>";
$archivos = [
    '../config/config.php',
    '../helpers/Session.php',
    '../routes/web.php',
    '../controllers/AuthController.php',
];

foreach ($archivos as $archivo) {
    $ruta = __DIR__ . '/' . $archivo;
    if (file_exists($ruta)) {
        echo "✅ <strong>$archivo</strong> existe<br>";
    } else {
        echo "❌ <strong>$archivo</strong> NO existe<br>";
    }
}

echo "<hr><h2>⚙️ Test config.php</h2>";
try {
    require_once __DIR__ . '/../config/config.php';
    echo "✅ config.php cargado<br>";
    
    if (defined('DB_HOST')) {
        echo "✅ DB_HOST definido: " . DB_HOST . "<br>";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<hr><h2>🔐 Test Session manual</h2>";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    echo "✅ Sesión iniciada<br>";
    echo "Session ID: " . session_id() . "<br>";
    
    $_SESSION['test'] = 'valor_prueba';
    
    if (isset($_SESSION['test'])) {
        echo "✅ Variable recuperada: " . $_SESSION['test'] . "<br>";
    }
}

echo "<hr><p><a href='/login'>🔙 Ir a Login</a></p>";
echo "</body></html>";
?>

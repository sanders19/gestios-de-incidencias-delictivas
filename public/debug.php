Set-Content public\test.php -Value @'
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Test</title></head><body>";
echo "<h1>🔍 TEST SIMPLE</h1><hr>";

// Test 1: PHP funcionando
echo "<h2>✅ PHP está funcionando</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Hora actual: " . date('Y-m-d H:i:s') . "<br>";

// Test 2: Archivos
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

// Test 3: Cargar config
echo "<hr><h2>⚙️ Test config.php</h2>";
try {
    require_once __DIR__ . '/../config/config.php';
    echo "✅ config.php cargado<br>";
    
    if (defined('DB_HOST')) {
        echo "✅ DB_HOST definido: " . DB_HOST . "<br>";
    }
    if (defined('DB_NAME')) {
        echo "✅ DB_NAME definido: " . DB_NAME . "<br>";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

// Test 4: Conexión a BD
echo "<hr><h2>🗄️ Test conexión a base de datos</h2>";
try {
    require_once __DIR__ . '/../config/Database.php';
    $db = Database::getInstance()->getConnection();
    
    if ($db) {
        echo "✅ Conexión a base de datos exitosa<br>";
        
        // Test query simple
        $stmt = $db->query("SELECT COUNT(*) as total FROM usuarios");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "✅ Total de usuarios en BD: " . $result['total'] . "<br>";
    } else {
        echo "❌ No se pudo conectar a la base de datos<br>";
    }
} catch (Exception $e) {
    echo "❌ Error de BD: " . $e->getMessage() . "<br>";
}

// Test 5: Session manual
echo "<hr><h2>🔐 Test Session manual</h2>";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    echo "✅ Sesión iniciada manualmente<br>";
    echo "Session ID: " . session_id() . "<br>";
    
    $_SESSION['test'] = 'valor_prueba';
    echo "✅ Variable de sesión guardada<br>";
    
    if (isset($_SESSION['test'])) {
        echo "✅ Variable de sesión recuperada: " . $_SESSION['test'] . "<br>";
    }
} else {
    echo "⚠️ Sesión ya estaba iniciada<br>";
}

echo "<hr>";
echo "<p><a href='/login'>🔙 Ir a Login</a></p>";
echo "</body></html>";
?>
'@

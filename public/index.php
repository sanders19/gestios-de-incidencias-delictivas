<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Manejar archivos estáticos
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$parsedUrl = parse_url($requestUri);
$requestPath = $parsedUrl['path'] ?? '/';

if (preg_match('/\.(css|js|jpg|jpeg|png|gif|svg|ico|woff|woff2|ttf|eot|pdf)$/i', $requestPath)) {
    $filePath = __DIR__ . $requestPath;
    
    if (file_exists($filePath) && is_file($filePath)) {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'pdf' => 'application/pdf',
        ];
        
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        header('Content-Type: ' . $mimeType);
        readfile($filePath);
        exit;
    }
}

// Cargar configuración
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/Session.php';

// Iniciar sesión
Session::start();

// Cargar rutas
$routes = require __DIR__ . '/../routes/web.php';

$path = $parsedUrl['path'] ?? '/';
if ($path === '') $path = '/';
if ($path !== '/' && substr($path, -1) === '/') {
    $path = rtrim($path, '/');
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
error_log("LOG Router: Incoming request: {$method} {$path}");

// Buscar ruta
$routeFound = false;
foreach ($routes as $pattern => $handler) {
    if (!preg_match('/^([A-Z|]+)\s+(.+)$/', $pattern, $matches)) {
        continue;
    }
    
    $allowedMethods = explode('|', $matches[1]);
    $routePath = $matches[2];

    if (!in_array($method, $allowedMethods)) {
        continue;
    }

    $regex = preg_quote($routePath, '/');
    $regex = preg_replace('/\\\\\(([a-zA-Z0-9_]+)\\\\\)/', '([a-zA-Z0-9\-_]+)', $regex);
    $regex = '/^' . $regex . '$/i';

    error_log("LOG Router: Checking pattern '{$pattern}' => regex '{$regex}' against path '{$path}'");

    if (preg_match($regex, $path, $matches)) {
        array_shift($matches);
        error_log("LOG Router: Matched pattern '{$pattern}' for path '{$path}'");

        if (is_callable($handler)) {
            call_user_func_array($handler, $matches);
        } else {
            [$controllerName, $methodName] = explode('@', $handler);
            
            // Determinar carpeta del controlador
            if (strpos($path, '/mesa/') === 0) {
                $controllerFile = __DIR__ . "/../controllers/mesa/{$controllerName}.php";
            } elseif (strpos($path, '/seincri/') === 0) {
                $controllerFile = __DIR__ . "/../controllers/seincri/{$controllerName}.php";
            } elseif (strpos($path, '/jefe/') === 0) {
                $controllerFile = __DIR__ . "/../controllers/jefe/{$controllerName}.php";
            } else {
                $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";
            }

            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                
                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    
                    if (method_exists($controller, $methodName)) {
                        call_user_func_array([$controller, $methodName], $matches);
                    } else {
                        http_response_code(500);
                        echo "Método '{$methodName}' no encontrado.";
                    }
                } else {
                    http_response_code(500);
                    echo "Clase '{$controllerName}' no encontrada.";
                }
            } else {
                http_response_code(500);
                echo "Controlador no encontrado: {$controllerFile}";
            }
        }
        $routeFound = true;
        break;
    }
}

if (!$routeFound) {
    http_response_code(404);
    echo "<h1>404 - Página no encontrada</h1>";
    echo "<p>Ruta: " . htmlspecialchars($path) . "</p>";
}
?>

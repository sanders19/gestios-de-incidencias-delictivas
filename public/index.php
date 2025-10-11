<?php
// Punto de entrada - Servidor PHP Built-in

// ✅ PASO 1: Manejar archivos estáticos PRIMERO (CSS, JS, imágenes, etc.)
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$parsedUrl = parse_url($requestUri);
$requestPath = $parsedUrl['path'] ?? '/';

// Verificar si es un archivo estático
if (preg_match('/\.(css|js|jpg|jpeg|png|gif|svg|ico|woff|woff2|ttf|eot|mp4|webm|mp3|pdf|txt)$/i', $requestPath)) {
    $filePath = __DIR__ . $requestPath;
    
    if (file_exists($filePath) && is_file($filePath)) {
        // Determinar tipo MIME
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
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
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'mp3' => 'audio/mpeg',
            'pdf' => 'application/pdf',
            'txt' => 'text/plain',
        ];
        
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        // Archivo estático no encontrado
        http_response_code(404);
        echo "Archivo no encontrado: " . htmlspecialchars($requestPath);
        exit;
    }
}

// ✅ PASO 2: Cargar configuración (solo si no es archivo estático)
require_once __DIR__ . '/../config/config.php';

// Cargar rutas
$routes = require_once __DIR__ . '/../routes/web.php';

// Obtener la ruta solicitada (solo la parte de la URL después del dominio)
$path = $parsedUrl['path'] ?? '/';

// Normalizar ruta: asegurar que empiece con /
if ($path === '') $path = '/';
if ($path !== '/' && substr($path, -1) === '/') {
    $path = rtrim($path, '/');
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Buscar coincidencia en rutas
$routeFound = false;
foreach ($routes as $pattern => $handler) {
    // Separar métodos y ruta
    if (!preg_match('/^([A-Z|]+)\s+(.+)$/', $pattern, $matches)) {
        continue;
    }
    $allowedMethods = explode('|', $matches[1]);
    $routePath = $matches[2];

    if (!in_array($method, $allowedMethods)) {
        continue;
    }

    // Convertir ruta a regex
    $regex = preg_quote($routePath, '/');
    $regex = preg_replace('/\\\\\(([a-zA-Z0-9_]+)\\\\\)/', '([a-zA-Z0-9\-_]+)', $regex);
    $regex = '/^' . $regex . '$/i';

    if (preg_match($regex, $path, $matches)) {
        array_shift($matches); // Eliminar coincidencia completa

        if (is_callable($handler)) {
            call_user_func_array($handler, $matches);
        } else {
            [$controllerName, $methodName] = explode('@', $handler);
            
            // Determinar archivo del controlador
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
                        http_response_code(404);
                        echo "Método '{$methodName}' no encontrado en el controlador.";
                    }
                } else {
                    http_response_code(404);
                    echo "Clase del controlador '{$controllerName}' no encontrada.";
                }
            } else {
                http_response_code(404);
                echo "Archivo del controlador no encontrado: {$controllerFile}";
            }
        }
        $routeFound = true;
        break;
    }
}

if (!$routeFound) {
    http_response_code(404);
    echo "<h1>404 - Página no encontrada</h1>";
    echo "<p><strong>Ruta solicitada:</strong> " . htmlspecialchars($path) . "</p>";
    echo "<p><strong>Método:</strong> " . htmlspecialchars($method) . "</p>";
    echo "<p><strong>Rutas disponibles:</strong></p><ul>";
    foreach (array_keys($routes) as $route) {
        echo "<li>" . htmlspecialchars($route) . "</li>";
    }
    echo "</ul>";
}
?>
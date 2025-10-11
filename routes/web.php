<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/RoleMiddleware.php';

// Rutas públicas
$routes = [
    'GET /' => function() {
        header('Location: /login');
        exit;
    },
    'GET|POST /login' => 'AuthController@login',
    'GET|POST /cambiar-contrasena' => 'AuthController@cambiarContrasena',
    'GET /logout' => 'AuthController@logout',
];

// Rutas protegidas por autenticación
$authRoutes = [
    // Mesa
    'GET /mesa/dashboard' => 'MesaDashboardController@index',
    'GET|POST /mesa/registro' => 'MesaRegistroController@index',
    'GET /mesa/busqueda' => 'MesaBusquedaController@index',
    'GET /mesa/reportes' => 'MesaReportesController@index',
    'GET /mesa/perfil' => 'MesaPerfilController@index',

    // SEINCRI
    'GET /seincri/dashboard' => 'SeincriDashboardController@index',
    'GET /seincri/atencion' => 'SeincriAtencionController@index',
    'GET|POST /seincri/atencion/actualizar/([a-zA-Z0-9\-]+)' => 'SeincriAtencionController@actualizarEstado',
    'GET /seincri/detalle/([a-zA-Z0-9\-]+)' => 'SeincriAtencionController@actualizarEstado',
    'GET /seincri/busqueda' => 'SeincriBusquedaController@index',
    'GET /seincri/reportes' => 'SeincriReportesController@index',
    'GET /seincri/perfil' => 'SeincriPerfilController@index',

    // Jefe
    'GET /jefe/dashboard' => 'JefeDashboardController@index',
    'GET /jefe/registro' => function() { header('Location: /jefe/dashboard'); },
    'GET|POST /jefe/atencion' => 'JefeAtencionController@index',
    'GET /jefe/asignacion' => 'JefeAsignacionController@index',
    'GET /jefe/busqueda' => 'JefeBusquedaController@index',
    'GET|POST /jefe/reportes' => 'JefeReportesController@index',
    'GET /jefe/perfil' => 'JefePerfilController@index',
    'GET|POST /jefe/crear-usuario' => 'JefeCrearUsuarioController@index',
];

// Aplicar middleware
foreach ($authRoutes as $pattern => $handler) {
    $routes[$pattern] = function() use ($handler, $pattern) {
        error_log("=== MIDDLEWARE ===");
        error_log("Pattern: " . $pattern);
        error_log("Handler: " . (is_string($handler) ? $handler : 'CLOSURE'));
        
        AuthMiddleware::check();
        
        // Si es una función anónima, ejecutarla directamente
        if (is_callable($handler) && !is_string($handler)) {
            error_log("Ejecutando closure directamente");
            call_user_func($handler);
            return;
        }
        
        // Si es un string con formato Controller@method
        if (is_string($handler) && strpos($handler, '@') !== false) {
            RoleMiddleware::check($handler);
            
            [$controller, $method] = explode('@', $handler);
            error_log("Controller extraído: " . $controller);
            error_log("Método extraído: " . $method);
            
            // Detectar namespace según la ruta
            $path = $_SERVER['REQUEST_URI'] ?? '/';
            
            // ✅ CORRECCIÓN: Ya NO agregamos el prefijo porque el controlador YA lo tiene
            if (strpos($path, '/mesa/') === 0) {
                $controllerFile = __DIR__ . "/../controllers/mesa/{$controller}.php";
                $controllerClass = $controller; // Sin agregar prefijo
            } elseif (strpos($path, '/seincri/') === 0) {
                $controllerFile = __DIR__ . "/../controllers/seincri/{$controller}.php";
                $controllerClass = $controller; // Sin agregar prefijo
            } elseif (strpos($path, '/jefe/') === 0) {
                $controllerFile = __DIR__ . "/../controllers/jefe/{$controller}.php";
                $controllerClass = $controller; // Sin agregar prefijo
            } else {
                $controllerFile = __DIR__ . "/../controllers/{$controller}.php";
                $controllerClass = $controller;
            }

            error_log("Archivo del controlador: " . $controllerFile);
            error_log("Clase del controlador: " . $controllerClass);

            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                if (class_exists($controllerClass)) {
                    $controllerInstance = new $controllerClass();
                    if (method_exists($controllerInstance, $method)) {
                        error_log("✅ Ejecutando {$controllerClass}@{$method}");
                        call_user_func_array([$controllerInstance, $method], func_get_args());
                    } else {
                        error_log("❌ Método no encontrado: {$method}");
                        http_response_code(404);
                        echo "Método no encontrado: {$method}";
                    }
                } else {
                    error_log("❌ Clase no encontrada: {$controllerClass}");
                    http_response_code(404);
                    echo "Clase no encontrada: {$controllerClass}";
                }
            } else {
                error_log("❌ Archivo no encontrado: {$controllerFile}");
                http_response_code(404);
                echo "Archivo no encontrado: {$controllerFile}";
            }
        }
    };
}

return $routes;
?>
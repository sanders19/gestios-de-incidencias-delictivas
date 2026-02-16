<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/RoleMiddleware.php';

// ========================================
// 🌐 RUTAS PÚBLICAS (sin autenticación)
// ========================================
$routes = [
    'GET /ping' => function() {
        error_log('LOG ROUTES: /ping atendido');
        echo 'pong';
        exit;
    },
    'GET /' => function() {
        header('Location: /login');
        exit;
    },
    'GET|POST /login' => 'AuthController@login',
    'GET|POST /cambiar-contrasena' => 'AuthController@cambiarContrasena',
    'GET /logout' => 'AuthController@logout',
];

// ========================================
// 👤 RUTAS PROTEGIDAS - ROL: MESA
// ========================================

// Dashboard principal
$routes['GET /mesa/dashboard'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('MesaDashboardController@index');
    require_once __DIR__ . '/../controllers/mesa/MesaDashboardController.php';
    $controller = new MesaDashboardController();
    $controller->index();
};

// Ver todos los registros del usuario
$routes['GET /mesa/mis-registros'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('MesaDashboardController@misRegistros');
    require_once __DIR__ . '/../controllers/mesa/MesaDashboardController.php';
    $controller = new MesaDashboardController();
    $controller->misRegistros();
};

// Registrar nueva incidencia
$routes['GET|POST /mesa/registro'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('MesaRegistroController@index');
    require_once __DIR__ . '/../controllers/mesa/MesaRegistroController.php';
    $controller = new MesaRegistroController();
    $controller->index();
};

// Ver detalle de incidencia
$routes['GET /mesa/detalle/(id)'] = function($id) {
    AuthMiddleware::check();
    RoleMiddleware::check('MesaDashboardController@verDetalle');
    require_once __DIR__ . '/../controllers/mesa/MesaDashboardController.php';
    $controller = new MesaDashboardController();
    $controller->verDetalle($id);
};

// Editar incidencia (solo Pendiente)
$routes['GET|POST /mesa/editar/(id)'] = function($id) {
    AuthMiddleware::check();
    RoleMiddleware::check('MesaDashboardController@editar');
    require_once __DIR__ . '/../controllers/mesa/MesaDashboardController.php';
    $controller = new MesaDashboardController();
    $controller->editar($id);
};

// Eliminar incidencia (solo Pendiente)
$routes['GET|POST /mesa/eliminar/(id)'] = function($id) {
    AuthMiddleware::check();
    RoleMiddleware::check('MesaDashboardController@eliminar');
    require_once __DIR__ . '/../controllers/mesa/MesaDashboardController.php';
    $controller = new MesaDashboardController();
    $controller->eliminar($id);
};

// Búsqueda de incidencias
$routes['GET /mesa/busqueda'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('MesaBusquedaController@index');
    require_once __DIR__ . '/../controllers/mesa/MesaBusquedaController.php';
    $controller = new MesaBusquedaController();
    $controller->index();
};

// Reportes básicos
$routes['GET|POST /mesa/reportes'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('MesaReportesController@index');
    require_once __DIR__ . '/../controllers/mesa/MesaReportesController.php';
    $controller = new MesaReportesController();
    $controller->index();
};

// Perfil de usuario
$routes['GET /mesa/perfil'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('MesaPerfilController@index');
    require_once __DIR__ . '/../controllers/mesa/MesaPerfilController.php';
    $controller = new MesaPerfilController();
    $controller->index();
};

// ========================================
// 🕵️ RUTAS PROTEGIDAS - ROL: SEINCRI
// ========================================

// Dashboard principal
$routes['GET /seincri/dashboard'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('SeincriDashboardController@index');
    require_once __DIR__ . '/../controllers/seincri/SeincriDashboardController.php';
    $controller = new SeincriDashboardController();
    $controller->index();
};

// Lista de casos asignados
$routes['GET /seincri/atencion'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('SeincriAtencionController@index');
    require_once __DIR__ . '/../controllers/seincri/SeincriAtencionController.php';
    $controller = new SeincriAtencionController();
    $controller->index();
};

// Actualizar estado de caso
$routes['GET|POST /seincri/atencion/actualizar/(id)'] = function($id) {
    AuthMiddleware::check();
    RoleMiddleware::check('SeincriAtencionController@actualizarEstado');
    require_once __DIR__ . '/../controllers/seincri/SeincriAtencionController.php';
    $controller = new SeincriAtencionController();
    $controller->actualizarEstado($id);
};

// Ver detalle de caso
$routes['GET /seincri/detalle/(id)'] = function($id) {
    AuthMiddleware::check();
    RoleMiddleware::check('SeincriAtencionController@verDetalle');
    require_once __DIR__ . '/../controllers/seincri/SeincriAtencionController.php';
    $controller = new SeincriAtencionController();
    $controller->verDetalle($id);
};

// Búsqueda de casos
$routes['GET /seincri/busqueda'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('SeincriBusquedaController@index');
    require_once __DIR__ . '/../controllers/seincri/SeincriBusquedaController.php';
    $controller = new SeincriBusquedaController();
    $controller->index();
};

// Reportes personales
$routes['GET|POST /seincri/reportes'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('SeincriReportesController@index');
    require_once __DIR__ . '/../controllers/seincri/SeincriReportesController.php';
    $controller = new SeincriReportesController();
    $controller->index();
};

// Perfil de usuario
$routes['GET /seincri/perfil'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('SeincriPerfilController@index');
    require_once __DIR__ . '/../controllers/seincri/SeincriPerfilController.php';
    $controller = new SeincriPerfilController();
    $controller->index();
};

// ========================================
// 👨‍💼 RUTAS PROTEGIDAS - ROL: JEFE
// ========================================

// Dashboard principal
$routes['GET /jefe/dashboard'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('JefeDashboardController@index');
    require_once __DIR__ . '/../controllers/jefe/JefeDashboardController.php';
    $controller = new JefeDashboardController();
    $controller->index();
};

// Registrar incidencia (redirige)
$routes['GET|POST /jefe/registro'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('JefeRegistroController@index');
    require_once __DIR__ . '/../controllers/jefe/JefeRegistroController.php';
    $controller = new JefeRegistroController();
    $controller->index();
};

// Asignar casos a SEINCRI
$routes['GET|POST /jefe/atencion'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('JefeAtencionController@index');
    require_once __DIR__ . '/../controllers/jefe/JefeAtencionController.php';
    $controller = new JefeAtencionController();
    $controller->index();
};

// ========================================
// 📋 ASIGNACIÓN DE INCIDENCIAS (Jefe)
// ========================================

// Ver incidencias pendientes para asignar
$routes['GET /jefe/asignacion'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('JefeAsignacionController@index');
    require_once __DIR__ . '/../controllers/jefe/JefeAsignacionController.php';
    $controller = new JefeAsignacionController();
    $controller->index();
};

// Asignar incidencia a SEINCRI (POST)
$routes['POST /jefe/asignacion/asignar/(id)'] = function($id) {
    AuthMiddleware::check();
    RoleMiddleware::check('JefeAsignacionController@asignar');
    require_once __DIR__ . '/../controllers/jefe/JefeAsignacionController.php';
    $controller = new JefeAsignacionController();
    $controller->asignar($id);
};

// Ver historial de asignaciones
$routes['GET /jefe/historial-asignaciones'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('JefeAsignacionController@historial');
    require_once __DIR__ . '/../controllers/jefe/JefeAsignacionController.php';
    $controller = new JefeAsignacionController();
    $controller->historial();
};

// ========================================
// 🔍 OTRAS FUNCIONES DEL JEFE
// ========================================

// Búsqueda avanzada (todas las incidencias)
$routes['GET /jefe/busqueda'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('JefeBusquedaController@index');
    require_once __DIR__ . '/../controllers/jefe/JefeBusquedaController.php';
    $controller = new JefeBusquedaController();
    $controller->index();
};

// Generación de reportes en PDF
$routes['GET|POST /jefe/reportes'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('JefeReportesController@index');
    require_once __DIR__ . '/../controllers/jefe/JefeReportesController.php';
    $controller = new JefeReportesController();
    $controller->index();
};

// 🔥 NUEVA RUTA: Eliminar reporte
$routes['GET /jefe/reportes/eliminar/(id_reporte)'] = function($id_reporte) {
    AuthMiddleware::check();
    RoleMiddleware::check('JefeReportesController@eliminar');
    require_once __DIR__ . '/../controllers/jefe/JefeReportesController.php';
    $controller = new JefeReportesController();
    $controller->eliminar($id_reporte);
};

// Perfil de usuario
$routes['GET /jefe/perfil'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('JefePerfilController@index');
    require_once __DIR__ . '/../controllers/jefe/JefePerfilController.php';
    $controller = new JefePerfilController();
    $controller->index();
};

// ========================================
// 👥 GESTIÓN DE USUARIOS (solo Jefe)
// ========================================

// Crear nuevo usuario
$routes['GET|POST /jefe/crear_usuario'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('JefeCrearUsuarioController@index');
    require_once __DIR__ . '/../controllers/jefe/JefeCrearUsuarioController.php';
    $controller = new JefeCrearUsuarioController();
    $controller->index();
};

// Lista de usuarios (filtro por rol: ?rol=mesa|seincri|jefe|all)
$routes['GET /jefe/usuarios'] = function() {
    AuthMiddleware::check();
    RoleMiddleware::check('JefeUsuariosController@index');
    require_once __DIR__ . '/../controllers/jefe/JefeUsuariosController.php';
    $controller = new JefeUsuariosController();
    $controller->index();
};

// Editar usuario existente
$routes['GET|POST /jefe/usuario/editar/(id)'] = function($id) {
    AuthMiddleware::check();
    RoleMiddleware::check('JefeUsuariosController@editar');
    require_once __DIR__ . '/../controllers/jefe/JefeUsuariosController.php';
    $controller = new JefeUsuariosController();
    $controller->editar($id);
};

// Restablecer contraseña de usuario
$routes['GET|POST /jefe/usuario/resetear/(id)'] = function($id) {
    AuthMiddleware::check();
    RoleMiddleware::check('JefeUsuariosController@resetear');
    require_once __DIR__ . '/../controllers/jefe/JefeUsuariosController.php';
    $controller = new JefeUsuariosController();
    $controller->resetear($id);
};

// Eliminar usuario (confirmación + ejecución)
$routes['GET|POST /jefe/usuario/eliminar/(id)'] = function($id) {
    AuthMiddleware::check();
    RoleMiddleware::check('JefeUsuariosController@eliminar');
    require_once __DIR__ . '/../controllers/jefe/JefeUsuariosController.php';
    $controller = new JefeUsuariosController();
    $controller->eliminar($id);
};

return $routes;

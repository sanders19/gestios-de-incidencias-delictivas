<?php

require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../helpers/Session.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        error_log("AuthController: Constructor llamado");
        $this->usuarioModel = new Usuario();
    }

    public function login() {
        error_log("=== AuthController::login() - INICIO ===");
        error_log("Método HTTP: " . $_SERVER['REQUEST_METHOD']);
        
        // Si ya está logueado, redirigir según rol
        if (Session::has('usuario')) {
            error_log("Usuario ya tiene sesión activa");
            $usuario = Session::get('usuario');
            error_log("Rol del usuario en sesión: " . $usuario['rol']);
            $this->redirigirPorRol($usuario['rol']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("=== PROCESANDO LOGIN POST ===");
            
            $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
            $contrasena = $_POST['contrasena'] ?? '';
            $rol_seleccionado = $_POST['rol'] ?? '';

            error_log("Usuario ingresado: " . $nombre_usuario);
            error_log("Rol seleccionado: " . $rol_seleccionado);
            error_log("Contraseña ingresada: " . (empty($contrasena) ? 'VACÍA' : 'OK (longitud: ' . strlen($contrasena) . ')'));

            // Validar que todos los campos estén llenos
            if (empty($nombre_usuario) || empty($contrasena) || empty($rol_seleccionado)) {
                error_log("❌ Validación fallida: campos vacíos");
                Session::setFlash('error', 'Por favor, completa todos los campos.');
                require_once __DIR__ . '/../views/auth/login.php';
                return;
            }

            // Verificar credenciales
            error_log("Llamando a usuarioModel->login()");
            $usuario = $this->usuarioModel->login($nombre_usuario, $contrasena);

            if ($usuario) {
                error_log("✅ Usuario encontrado en BD");
                error_log("Datos del usuario: " . print_r($usuario, true));
                error_log("Rol en BD: " . $usuario['rol']);
                error_log("Rol seleccionado: " . $rol_seleccionado);
                
                // Verificar que el rol seleccionado coincida con el del usuario
                if ($usuario['rol'] !== $rol_seleccionado) {
                    error_log("❌ Rol no coincide");
                    Session::setFlash('error', 'El rol seleccionado no coincide con tu cuenta.');
                    require_once __DIR__ . '/../views/auth/login.php';
                    return;
                }

                error_log("✅ Rol coincide, iniciando sesión");
                
                // Iniciar sesión
                Session::set('usuario', $usuario);
                error_log("Sesión guardada");
                
                $this->usuarioModel->actualizarUltimoAcceso($usuario['id_usuario']);
                error_log("Último acceso actualizado");

                // Redirigir según flujo de primer inicio
                if ($usuario['es_primer_inicio']) {
                    error_log("Es primer inicio, redirigiendo a cambiar contraseña");
                    header('Location: /cambiar-contrasena');
                    exit;
                } else {
                    error_log("NO es primer inicio, redirigiendo según rol");
                    error_log("Llamando a redirigirPorRol con: " . $usuario['rol']);
                    $this->redirigirPorRol($usuario['rol']);
                    error_log("⚠️ DESPUÉS de redirigirPorRol (NO DEBERÍA LLEGAR AQUÍ)");
                }
            } else {
                error_log("❌ Credenciales incorrectas");
                Session::setFlash('error', 'Credenciales incorrectas. Verifica tu usuario y contraseña.');
                require_once __DIR__ . '/../views/auth/login.php';
                return;
            }
        } else {
            error_log("Mostrando formulario de login (GET)");
            require_once __DIR__ . '/../views/auth/login.php';
        }
        
        error_log("=== FIN AuthController::login() ===");
    }

    public function cambiarContrasena() {
        error_log("AuthController::cambiarContrasena() - Inicio");
        
        if (!Session::has('usuario')) {
            error_log("Sin sesión, redirigiendo a login");
            header('Location: /login');
            exit;
        }

        $usuario = Session::get('usuario');
        error_log("Usuario en sesión: " . $usuario['nombre_usuario']);

        if (!$usuario['es_primer_inicio']) {
            error_log("NO es primer inicio, redirigiendo a dashboard");
            $this->redirigirPorRol($usuario['rol']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nueva = $_POST['nueva_contrasena'] ?? '';
            $confirmar = $_POST['confirmar_contrasena'] ?? '';

            if (empty($nueva) || empty($confirmar)) {
                Session::setFlash('error', 'Todos los campos son obligatorios.');
            } elseif ($nueva !== $confirmar) {
                Session::setFlash('error', 'Las contraseñas no coinciden.');
            } elseif (strlen($nueva) < 6) {
                Session::setFlash('error', 'La contraseña debe tener al menos 6 caracteres.');
            } else {
                if ($this->usuarioModel->cambiarContrasena($usuario['id_usuario'], $nueva)) {
                    error_log("Contraseña cambiada exitosamente");
                    Session::destroy();
                    Session::setFlash('success', 'Contraseña actualizada correctamente.');
                    header('Location: /login');
                    exit;
                } else {
                    error_log("Error al cambiar contraseña");
                    Session::setFlash('error', 'Error al actualizar la contraseña.');
                }
            }
        }

        require_once __DIR__ . '/../views/auth/cambiar_contraseña.php';
    }

    public function logout() {
        error_log("AuthController::logout() - Cerrando sesión");
        Session::destroy();
        Session::setFlash('info', 'Has cerrado sesión correctamente.');
        header('Location: /login');
        exit;
    }

    private function redirigirPorRol($rol) {
        error_log("=== redirigirPorRol() ===");
        error_log("Rol recibido: " . $rol);
        
        switch ($rol) {
            case 'mesa':
                error_log("Redirigiendo a: /mesa/dashboard");
                header('Location: /mesa/dashboard');
                break;
            case 'seincri':
                error_log("Redirigiendo a: /seincri/dashboard");
                header('Location: /seincri/dashboard');
                break;
            case 'jefe':
                error_log("Redirigiendo a: /jefe/dashboard");
                header('Location: /jefe/dashboard');
                break;
            default:
                error_log("⚠️ Rol desconocido, redirigiendo a login");
                header('Location: /login');
        }
        
        error_log("Header enviado, ejecutando exit");
        exit;
    }
}
?>
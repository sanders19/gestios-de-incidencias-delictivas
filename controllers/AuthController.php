<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../helpers/Session.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function login() {
        error_log("LOG AuthController: Entrando a login - METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN'));
        $error = ''; // Variable para errores
        $usuarioInput = '';
        $rolSeleccionado = '';

        // Si ya hay sesión activa, redirigir según rol
        if (Session::has('usuario')) {
            $usuario = Session::get('usuario');
            // Validar que la sesión tenga la estructura esperada para evitar bucles de redirección
            if (!is_array($usuario) || empty($usuario['rol'])) {
                error_log("LOG AuthController: Sesión inválida o incompleta, destruyendo sesión");
                Session::destroy();
            } else {
                $this->redirigirPorRol($usuario['rol']);
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioInput = trim($_POST['nombre_usuario'] ?? '');
            $contrasena = $_POST['contrasena'] ?? '';
            $rolSeleccionado = strtolower($_POST['rol'] ?? '');

            // Validar campos
            if (empty($usuarioInput) || empty($contrasena) || empty($rolSeleccionado)) {
                $error = 'Por favor, completa todos los campos.';
            } else {
                $usuario = $this->usuarioModel->login($usuarioInput, $contrasena);

                if ($usuario) {
                    $usuario['rol'] = strtolower($usuario['rol']);

                    if ($usuario['rol'] !== $rolSeleccionado) {
                        $error = 'El rol seleccionado no coincide con tu cuenta.';
                    } else {
                        // Guardar sesión usando Session helper
                        Session::set('usuario', $usuario);

                        // Actualizar último acceso
                        $this->usuarioModel->actualizarUltimoAcceso($usuario['id_usuario']);

                        // Redirigir según primer inicio
                        if ($usuario['es_primer_inicio']) {
                            header('Location: /cambiar-contrasena');
                            exit;
                        } else {
                            $this->redirigirPorRol($usuario['rol']);
                        }
                    }
                } else {
                    $error = 'Credenciales incorrectas. Verifica tu usuario y contraseña.';
                }
            }
        }

        // Cargar vista de login pasando variables
        error_log("LOG AuthController: Se renderizará la vista de login. Error actual: " . ($error ?? '')); 
        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout() {
        Session::destroy();
        header('Location: /login');
        exit;
    }

    public function cambiarContrasena() {
        // Verificar que el usuario esté autenticado
        if (!Session::has('usuario')) {
            header('Location: /login');
            exit;
        }

        $usuario = Session::get('usuario');
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nuevaContrasena = $_POST['nueva_contrasena'] ?? '';
            $confirmarContrasena = $_POST['confirmar_contrasena'] ?? '';

            if (empty($nuevaContrasena) || empty($confirmarContrasena)) {
                $error = 'Por favor, completa todos los campos.';
            } elseif ($nuevaContrasena !== $confirmarContrasena) {
                $error = 'Las contraseñas no coinciden.';
            } elseif (strlen($nuevaContrasena) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres.';
            } else {
                // Actualizar contraseña
                $resultado = $this->usuarioModel->cambiarContrasena(
                    $usuario['id_usuario'],
                    $nuevaContrasena
                );

                if ($resultado) {
                    // Actualizar sesión
                    $usuario['es_primer_inicio'] = false;
                    Session::set('usuario', $usuario);

                    // Redirigir al dashboard
                    $this->redirigirPorRol($usuario['rol']);
                    exit;
                } else {
                    $error = 'Error al cambiar la contraseña.';
                }
            }
        }

        // Cargar vista de cambio de contraseña
        require __DIR__ . '/../views/auth/cambiar_contrasena.php';
    }

    private function redirigirPorRol($rol) {
        switch ($rol) {
            case 'mesa':
                header('Location: /mesa/dashboard');
                break;
            case 'seincri':
                header('Location: /seincri/dashboard');
                break;
            case 'jefe':
                header('Location: /jefe/dashboard');
                break;
            default:
                header('Location: /login');
        }
        exit;
    }
}

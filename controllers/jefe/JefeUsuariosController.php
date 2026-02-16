<?php
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../helpers/Session.php';

class JefeUsuariosController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function index() {
        // Verificaciones de acceso
        AuthMiddleware::check();
        RoleMiddleware::check('JefeUsuariosController@index');

        $rol = $_GET['rol'] ?? 'all';
        $rol = in_array($rol, ['mesa', 'seincri', 'jefe', 'all']) ? $rol : 'all';

        if ($rol === 'all') {
            $usuarios = $this->usuarioModel->listarTodosUsuarios();
        } else {
            $usuarios = $this->usuarioModel->listarUsuariosPorRol($rol);
        }

        // Contadores por rol para el header
        $counts = [
            'mesa' => count($this->usuarioModel->listarUsuariosPorRol('mesa')),
            'seincri' => count($this->usuarioModel->listarUsuariosPorRol('seincri')),
            'jefe' => count($this->usuarioModel->listarUsuariosPorRol('jefe')),
            'total' => count($this->usuarioModel->listarTodosUsuarios())
        ];

        // Pasar datos a la vista
        require __DIR__ . '/../../views/jefe/usuarios.php';
    }

    public function editar($id) {
        AuthMiddleware::check();
        RoleMiddleware::check('JefeUsuariosController@editar');

        $usuario = $this->usuarioModel->obtenerPorId($id);
        if (!$usuario) {
            Session::setFlash('error', 'Usuario no encontrado');
            header('Location: /jefe/usuarios');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre_usuario = $_POST['nombre_usuario'] ?? '';
            $nombre_completo = $_POST['nombre_completo'] ?? '';
            $rol = $_POST['rol'] ?? '';
            $comisaria = $_POST['comisaria'] ?? '';

            if ($this->usuarioModel->updateUsuario($id, $nombre_usuario, $nombre_completo, $rol, $comisaria)) {
                Session::setFlash('success', 'Usuario actualizado correctamente');
            } else {
                Session::setFlash('error', 'Error al actualizar usuario');
            }

            header('Location: /jefe/usuarios');
            exit;
        }

        require __DIR__ . '/../../views/jefe/usuario_editar.php';
    }

    public function resetear($id) {
        AuthMiddleware::check();
        RoleMiddleware::check('JefeUsuariosController@resetear');

        $usuario = $this->usuarioModel->obtenerPorId($id);
        if (!$usuario) {
            Session::setFlash('error', 'Usuario no encontrado');
            header('Location: /jefe/usuarios');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nueva = $_POST['nueva_contrasena'] ?? '';
            $confirmar = $_POST['confirmar_contrasena'] ?? '';

            if (empty($nueva) || $nueva !== $confirmar || strlen($nueva) < 6) {
                Session::setFlash('error', 'Las contraseñas no coinciden o son muy cortas');
                header('Location: /jefe/usuario/resetear/' . $id);
                exit;
            }

            if ($this->usuarioModel->cambiarContrasena($id, $nueva)) {
                Session::setFlash('success', 'Contraseña restablecida');
            } else {
                Session::setFlash('error', 'Error al restablecer la contraseña');
            }

            header('Location: /jefe/usuarios');
            exit;
        }

        require __DIR__ . '/../../views/jefe/usuario_resetear.php';
    }

    public function eliminar($id) {
        AuthMiddleware::check();
        RoleMiddleware::check('JefeUsuariosController@eliminar');

        $usuario = $this->usuarioModel->obtenerPorId($id);
        if (!$usuario) {
            Session::setFlash('error', 'Usuario no encontrado');
            header('Location: /jefe/usuarios');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->usuarioModel->deleteUsuario($id)) {
                Session::setFlash('success', 'Usuario eliminado');
            } else {
                Session::setFlash('error', 'Error al eliminar usuario');
            }
            header('Location: /jefe/usuarios');
            exit;
        }

        require __DIR__ . '/../../views/jefe/usuario_eliminar.php';
    }
}

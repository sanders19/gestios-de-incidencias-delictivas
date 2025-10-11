<?php
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../helpers/Validator.php';
require_once __DIR__ . '/../../helpers/Session.php';

class JefeCrearUsuarioController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_POST['id_usuario'];
            $nombre_usuario = $_POST['nombre_usuario'];
            $contrasena = $_POST['contrasena'];
            $rol = $_POST['rol'];
            $nombre_completo = $_POST['nombre_completo'];
            $comisaria = $_POST['comisaria'];

            if ($this->usuarioModel->crearUsuario($id_usuario, $nombre_usuario, $contrasena, $rol, $nombre_completo, $comisaria)) {
                Session::setFlash('success', 'Usuario creado exitosamente');
            } else {
                Session::setFlash('error', 'Error al crear usuario');
            }
        }

        require_once __DIR__ . '/../../views/jefe/crear_usuario.php';
    }
}
?>
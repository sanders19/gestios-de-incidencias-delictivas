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
        // Precalcular siguientes IDs para cada rol para mostrarlos en la vista
        $roles = ['mesa', 'seincri', 'jefe'];
        $siguientes = [];
        foreach ($roles as $r) {
            $siguientes[$r] = $this->usuarioModel->generarSiguienteId($r);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Generar id del lado del servidor según el rol recibido (más seguro que confiar en el input)
            $rol = $_POST['rol'];
            $id_usuario = $this->usuarioModel->generarSiguienteId($rol);
            $nombre_usuario = $_POST['nombre_usuario'];
            $contrasena = $_POST['contrasena'];
            $nombre_completo = $_POST['nombre_completo'];
            $comisaria = $_POST['comisaria'];

            if ($this->usuarioModel->crearUsuario(
                $id_usuario,
                $nombre_usuario,
                $contrasena,
                $rol,
                $nombre_completo,
                $comisaria
            )) {
                Session::setFlash('success', 'Usuario creado exitosamente');
            } else {
                Session::setFlash('error', 'Error al crear usuario');
            }

            // Recalcular siguientes ya que se pudo haber creado uno nuevo
            foreach ($roles as $r) {
                $siguientes[$r] = $this->usuarioModel->generarSiguienteId($r);
            }
        }

        // Pasar $siguientes a la vista
        require_once __DIR__ . '/../../views/jefe/crear_usuario.php';
    }
}

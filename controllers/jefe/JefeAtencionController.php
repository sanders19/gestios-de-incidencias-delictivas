<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../models/Asignacion.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../helpers/Session.php';

class JefeAtencionController {
    private $incidenciaModel;
    private $asignacionModel;
    private $usuarioModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
        $this->asignacionModel = new Asignacion();
        $this->usuarioModel   = new Usuario();
    }

    public function index() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_incidencia = $_POST['id_incidencia'];
            $asignado_a    = $_POST['asignado_a'];
            $jefe_id       = Session::get('usuario')['id_usuario'];

            if ($this->asignacionModel->asignar($id_incidencia, $asignado_a, $jefe_id)) {
                Session::setFlash('success', 'Incidencia asignada correctamente');
                header('Location: /jefe/asignacion');
                exit;
            } else {
                Session::setFlash('error', 'Error al asignar');
            }
        }

        // 
        $incidencias_pendientes = $this->incidenciaModel->listarTodas(['estado' => 'Pendiente']);
        $seincri_usuarios       = $this->usuarioModel->listarUsuariosPorRol('seincri');

        require_once __DIR__ . '/../../views/jefe/atencion.php';
    }
}
?>

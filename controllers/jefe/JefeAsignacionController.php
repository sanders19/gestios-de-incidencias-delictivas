<?php
require_once __DIR__ . '/../../models/Asignacion.php';
require_once __DIR__ . '/../../helpers/Session.php';

class JefeAsignacionController {
    private $asignacionModel;

    public function __construct() {
        $this->asignacionModel = new Asignacion();
    }

    public function index() {
        $asignaciones = $this->asignacionModel->listarAsignaciones();
        require_once __DIR__ . '/../../views/jefe/asignacion.php';
    }
}
?>
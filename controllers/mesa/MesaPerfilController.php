<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../helpers/Session.php';

class MesaPerfilController {
    private $incidenciaModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
    }

    public function index() {
        $usuario = Session::get('usuario');
        $totalRegistradas = count($this->incidenciaModel->listarPorRegistrado($usuario['id_usuario']));

        require_once __DIR__ . '/../../views/mesa/perfil.php';
    }
}
?>
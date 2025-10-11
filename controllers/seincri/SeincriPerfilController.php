<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../helpers/Session.php';

class SeincriPerfilController {
    private $incidenciaModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
    }

    public function index() {
        $usuario = Session::get('usuario');
        $casos = $this->incidenciaModel->listarPorAsignado($usuario['id_usuario']);
        $totalAsignados = count($casos);
        $resueltos = count(array_filter($casos, fn($c) => $c['estado'] === 'Resuelto'));

        require_once __DIR__ . '/../../views/seincri/perfil.php';
    }
}
?>
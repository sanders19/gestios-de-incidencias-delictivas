<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../helpers/Session.php';

class SeincriDashboardController {
    private $incidenciaModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
    }

    public function index() {
        $usuario = Session::get('usuario');

        $todos = $this->incidenciaModel->listarPorAsignado($usuario['id_usuario']);
        $investigando = array_filter($todos, fn($i) => $i['estado'] === 'Investigando');
        $resueltos = array_filter($todos, fn($i) => $i['estado'] === 'Resuelto');
        $urgentes = array_filter($todos, fn($i) => $i['prioridad'] === 'Alta' && $i['estado'] !== 'Resuelto');

        require_once __DIR__ . '/../../views/seincri/dashboard.php';
    }
}
?>
<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../helpers/Session.php';

class MesaDashboardController {
    private $incidenciaModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
    }

    public function index() {
        $usuario = Session::get('usuario');
        $hoy = date('Y-m-d');

        // Incidencias registradas hoy
        $hoyFiltros = ['fecha_desde' => $hoy, 'fecha_hasta' => $hoy];
        $incidenciasHoy = $this->incidenciaModel->listarPorRegistrado($usuario['id_usuario'], $hoyFiltros);

        // Últimos registros (máximo 10)
        $ultimos = $this->incidenciaModel->listarPorRegistrado($usuario['id_usuario'], []);

        require_once __DIR__ . '/../../views/mesa/dashboard.php';
    }
}
?>
<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../helpers/Session.php';

class JefePerfilController {
    private $incidenciaModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
    }

    public function index() {
        $todas = $this->incidenciaModel->listarTodas();
        $totalIncidencias = count($todas);
        $resueltas = count(array_filter($todas, fn($i) => $i['estado'] === 'Resuelto'));

        require_once __DIR__ . '/../../views/jefe/perfil.php';
    }
}
?>
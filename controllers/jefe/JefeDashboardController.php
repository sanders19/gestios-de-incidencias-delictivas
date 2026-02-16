<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../helpers/Session.php';

class JefeDashboardController {
    private $incidenciaModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
    }

    public function index() {
        $todas = $this->incidenciaModel->listarTodas();
        $pendientes = array_filter($todas, fn($i) => $i['estado'] === 'Pendiente');
        $resueltas = array_filter($todas, fn($i) => $i['estado'] === 'Resuelto');

        // Estadísticas por agente SEINCRI
        $agentes = [];
        foreach ($todas as $inc) {
            if (!empty($inc['asignado_a'])) {
                $agente = $inc['asignado_a'];
                if (!isset($agentes[$agente])) {
                    $agentes[$agente] = ['total' => 0, 'resueltos' => 0];
                }
                $agentes[$agente]['total']++;
                if ($inc['estado'] === 'Resuelto') {
                    $agentes[$agente]['resueltos']++;
                }
            }
        }

        require_once __DIR__ . '/../../views/jefe/dashboard.php';
    }
}
?>
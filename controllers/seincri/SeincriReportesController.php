<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../helpers/Session.php';

class SeincriReportesController {
    private $incidenciaModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
    }

    public function index() {
        $estadisticas = [];
        $filtros = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filtros = [
                'periodo' => $_POST['periodo'] ?? '',
                'tipo_delito' => $_POST['tipo_delito'] ?? '',
                'id_zona' => $_POST['id_zona'] ?? ''
            ];

            $usuario = Session::get('usuario');
            $resultados = $this->incidenciaModel->listarPorAsignado($usuario['id_usuario'], $filtros);

            $estadisticas = [
                'total_asignados' => count($resultados),
                'resueltos' => count(array_filter($resultados, fn($i) => $i['estado'] === 'Resuelto'))
            ];
        }

        $pdo = Database::getInstance()->getConnection();
        $tipos = $pdo->query("SELECT DISTINCT tipo_delito FROM DelitosClasificaciones ORDER BY tipo_delito")->fetchAll();
        $zonas = $pdo->query("SELECT * FROM Zonas ORDER BY nombre")->fetchAll();

        require_once __DIR__ . '/../../views/seincri/reportes.php';
    }
}
?>
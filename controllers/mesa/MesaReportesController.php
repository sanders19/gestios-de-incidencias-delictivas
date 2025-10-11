<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../helpers/Session.php';

class MesaReportesController {
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
            $resultados = $this->incidenciaModel->listarPorRegistrado($usuario['id_usuario'], $filtros);

            $estadisticas = [
                'total' => count($resultados),
                'por_tipo' => []
            ];

            foreach ($resultados as $inc) {
                $tipo = $inc['tipo_delito'];
                if (!isset($estadisticas['por_tipo'][$tipo])) {
                    $estadisticas['por_tipo'][$tipo] = 0;
                }
                $estadisticas['por_tipo'][$tipo]++;
            }
        }

        // Cargar listas
        $pdo = Database::getInstance()->getConnection();
        $tipos = $pdo->query("SELECT DISTINCT tipo_delito FROM DelitosClasificaciones ORDER BY tipo_delito")->fetchAll();
        $zonas = $pdo->query("SELECT * FROM Zonas ORDER BY nombre")->fetchAll();

        require_once __DIR__ . '/../../views/mesa/reportes.php';
    }
}
?>
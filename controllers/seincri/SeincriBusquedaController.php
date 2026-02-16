<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../helpers/Session.php';

class SeincriBusquedaController {
    private $incidenciaModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
    }

    public function index() {
        $resultados = [];
        $filtros = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filtros = [
                'tipo_delito' => $_POST['tipo_delito'] ?? '',
                'estado' => $_POST['estado'] ?? '',
                'id_zona' => $_POST['id_zona'] ?? '',
                'fecha_desde' => $_POST['fecha_desde'] ?? '',
                'fecha_hasta' => $_POST['fecha_hasta'] ?? ''
            ];
        }

        $usuario = Session::get('usuario');
        $resultados = $this->incidenciaModel->listarPorAsignado($usuario['id_usuario'], $filtros);

        $pdo = Database::getInstance()->getConnection();
        $tipos = $pdo->query("SELECT DISTINCT tipo_delito FROM DelitosClasificaciones ORDER BY tipo_delito")->fetchAll();
        $zonas = $pdo->query("SELECT * FROM Zonas ORDER BY nombre")->fetchAll();

        require_once __DIR__ . '/../../views/seincri/busqueda.php';
    }
}
?>
<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../helpers/Session.php';

class JefeBusquedaController {
    private $incidenciaModel;
    private $usuarioModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
        $this->usuarioModel = new Usuario();
    }

    public function index() {
        $resultados = [];
        $filtros = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filtros = [
                'tipo_delito' => $_POST['tipo_delito'] ?? '',
                'estado' => $_POST['estado'] ?? '',
                'id_zona' => $_POST['id_zona'] ?? '',
                'asignado_a' => $_POST['asignado_a'] ?? '',
                'registrado_por' => $_POST['registrado_por'] ?? '',
                'fecha_desde' => $_POST['fecha_desde'] ?? '',
                'fecha_hasta' => $_POST['fecha_hasta'] ?? ''
            ];
        }

        $resultados = $this->incidenciaModel->listarTodas($filtros);

        $pdo = Database::getInstance()->getConnection();
        $tipos = $pdo->query("SELECT DISTINCT tipo_delito FROM DelitosClasificaciones ORDER BY tipo_delito")->fetchAll();
        $zonas = $pdo->query("SELECT * FROM Zonas ORDER BY nombre")->fetchAll();
        $mesaUsuarios = $this->usuarioModel->listarUsuariosPorRol('mesa');
        $seincriUsuarios = $this->usuarioModel->listarUsuariosPorRol('seincri');

        require_once __DIR__ . '/../../views/jefe/busqueda.php';
    }
}
?>
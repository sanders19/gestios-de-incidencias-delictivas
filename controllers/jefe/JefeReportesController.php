<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../models/Reporte.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../helpers/PDF.php';
require_once __DIR__ . '/../../helpers/Session.php';

class JefeReportesController {
    private $incidenciaModel;
    private $reporteModel;
    private $usuarioModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
        $this->reporteModel = new Reporte();
        $this->usuarioModel = new Usuario();
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filtros = [
                'periodo' => $_POST['periodo'] ?? 'Mes',
                'tipo_delito' => $_POST['tipo_delito'] ?? null,
                'id_zona' => $_POST['id_zona'] ?? null,
                'id_asignado_a' => $_POST['id_asignado_a'] ?? null,
                'id_registrado_por' => $_POST['id_registrado_por'] ?? null
            ];

            $resultados = $this->incidenciaModel->listarTodas($filtros);

            $datos = [
                'total_incidencias' => count($resultados),
                'por_estado' => [],
                'por_tipo' => []
            ];

            foreach ($resultados as $inc) {
                // Por estado
                $estado = $inc['estado'];
                if (!isset($datos['por_estado'][$estado])) $datos['por_estado'][$estado] = 0;
                $datos['por_estado'][$estado]++;

                // Por tipo
                $tipo = $inc['tipo_delito'];
                if (!isset($datos['por_tipo'][$tipo])) $datos['por_tipo'][$tipo] = 0;
                $datos['por_tipo'][$tipo]++;
            }

            // Guardar en BD
            $usuario = Session::get('usuario');
            $rutaPDF = 'uploads/reportes/reporte-' . time() . '.pdf';
            $reporteData = [
                'generado_por' => $usuario['id_usuario'],
                'periodo' => $filtros['periodo'],
                'tipo_delito' => $filtros['tipo_delito'],
                'id_zona' => $filtros['id_zona'],
                'id_asignado_a' => $filtros['id_asignado_a'],
                'id_registrado_por' => $filtros['id_registrado_por'],
                'datos_reporte' => $datos,
                'ruta_exportacion' => $rutaPDF
            ];

            $this->reporteModel->crear($reporteData);

            // Generar PDF
            $pdf = new PDF("Reporte Policial - " . $filtros['periodo']);
            $pdf->generarReporte($reporteData, __DIR__ . '/../../public/' . $rutaPDF);

            Session::setFlash('success', 'Reporte generado y guardado en PDF.');
            header('Location: /jefe/reportes');
            exit;
        }

        // Cargar listas
        $pdo = Database::getInstance()->getConnection();
        $tipos = $pdo->query("SELECT DISTINCT tipo_delito FROM DelitosClasificaciones ORDER BY tipo_delito")->fetchAll();
        $zonas = $pdo->query("SELECT * FROM Zonas ORDER BY nombre")->fetchAll();
        $mesaUsuarios = $this->usuarioModel->listarUsuariosPorRol('mesa');
        $seincriUsuarios = $this->usuarioModel->listarUsuariosPorRol('seincri');

        require_once __DIR__ . '/../../views/jefe/reportes.php';
    }
}
?>
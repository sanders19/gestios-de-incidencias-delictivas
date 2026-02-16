<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../models/Reporte.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../models/Database.php';
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
                'por_tipo' => [],
                'por_zona' => [],
                'por_prioridad' => [],
                'rendimiento_seincri' => [],
                'rendimiento_mesa' => []
            ];

            foreach ($resultados as $inc) {
                $estado = $inc['estado'];
                if (!isset($datos['por_estado'][$estado])) $datos['por_estado'][$estado] = 0;
                $datos['por_estado'][$estado]++;

                $tipo = $inc['tipo_delito'];
                if (!isset($datos['por_tipo'][$tipo])) $datos['por_tipo'][$tipo] = 0;
                $datos['por_tipo'][$tipo]++;

                if (!empty($inc['id_zona'])) {
                    $zona = $inc['id_zona'];
                    if (!isset($datos['por_zona'][$zona])) $datos['por_zona'][$zona] = 0;
                    $datos['por_zona'][$zona]++;
                }

                $prioridad = $inc['prioridad'] ?? 'Media';
                if (!isset($datos['por_prioridad'][$prioridad])) $datos['por_prioridad'][$prioridad] = 0;
                $datos['por_prioridad'][$prioridad]++;

                if (!empty($inc['asignado_a'])) {
                    $agente = $inc['asignado_a'];
                    if (!isset($datos['rendimiento_seincri'][$agente])) {
                        $datos['rendimiento_seincri'][$agente] = [
                            'total' => 0,
                            'resueltos' => 0,
                            'investigando' => 0,
                            'recibidos' => 0
                        ];
                    }
                    $datos['rendimiento_seincri'][$agente]['total']++;
                    
                    if ($inc['estado'] === 'Resuelto') {
                        $datos['rendimiento_seincri'][$agente]['resueltos']++;
                    } elseif ($inc['estado'] === 'Investigando') {
                        $datos['rendimiento_seincri'][$agente]['investigando']++;
                    } elseif ($inc['estado'] === 'Recibido') {
                        $datos['rendimiento_seincri'][$agente]['recibidos']++;
                    }
                }

                if (!empty($inc['registrado_por'])) {
                    $mesa = $inc['registrado_por'];
                    if (!isset($datos['rendimiento_mesa'][$mesa])) {
                        $datos['rendimiento_mesa'][$mesa] = 0;
                    }
                    $datos['rendimiento_mesa'][$mesa]++;
                }
            }

            $pdo = Database::getInstance()->getConnection();
            $zonasNombres = [];
            if (!empty($datos['por_zona'])) {
                $stmt = $pdo->query("SELECT id_zona, nombre FROM Zonas");
                while ($row = $stmt->fetch()) {
                    $zonasNombres[$row['id_zona']] = $row['nombre'];
                }
            }

            $usuariosNombres = [];
            $stmt = $pdo->query("SELECT id_usuario, nombre_completo FROM Usuarios");
            while ($row = $stmt->fetch()) {
                $usuariosNombres[$row['id_usuario']] = $row['nombre_completo'];
            }

            $usuario = Session::get('usuario');
            $rutaPDF = 'uploads/reportes/reporte-' . time() . '.pdf';
            
            $reporteData = [
                'generado_por' => $usuario['id_usuario'],
                'periodo' => $filtros['periodo'],
                'tipo_delito' => $filtros['tipo_delito'],
                'id_zona' => $filtros['id_zona'],
                'id_asignado_a' => $filtros['id_asignado_a'],
                'id_registrado_por' => $filtros['id_registrado_por'],
                'datos_reporte' => json_encode($datos),
                'ruta_exportacion' => $rutaPDF
            ];

            $this->reporteModel->crear($reporteData);

            $pdfData = [
                'periodo' => $filtros['periodo'],
                'tipo_delito' => $filtros['tipo_delito'] ?: 'Todos',
                'zona_nombre' => $filtros['id_zona'] ? ($zonasNombres[$filtros['id_zona']] ?? 'Zona ' . $filtros['id_zona']) : 'Todas',
                'asignado_a_nombre' => $filtros['id_asignado_a'] ? ($usuariosNombres[$filtros['id_asignado_a']] ?? $filtros['id_asignado_a']) : 'Todos',
                'registrado_por_nombre' => $filtros['id_registrado_por'] ? ($usuariosNombres[$filtros['id_registrado_por']] ?? $filtros['id_registrado_por']) : 'Todos',
                'datos_reporte' => $datos,
                'zonas_nombres' => $zonasNombres,
                'usuarios_nombres' => $usuariosNombres,
                'generado_por' => $usuario['nombre_completo'],
                'fecha_generacion' => date('d/m/Y H:i')
            ];

            // 🔥 LIMPIAR BUFFER Y SUPRIMIR WARNINGS
            ob_clean();
            error_reporting(0);

            $rutaCompleta = __DIR__ . '/../../public/' . $rutaPDF;
            $pdf = new PDF("REPORTE POLICIAL - " . strtoupper($filtros['periodo']));
            $pdf->generarReporte($pdfData, $rutaCompleta);

            error_reporting(E_ALL);

            Session::setFlash('success', 'Reporte generado y guardado exitosamente.');
            header('Location: /jefe/reportes');
            exit;
        }
        

        $pdo = Database::getInstance()->getConnection();
        $tipos = $pdo->query("SELECT DISTINCT tipo_delito FROM DelitosClasificaciones ORDER BY tipo_delito")->fetchAll();
        $zonas = $pdo->query("SELECT * FROM Zonas ORDER BY nombre")->fetchAll();
        $mesaUsuarios = $this->usuarioModel->listarUsuariosPorRol('mesa');
        $seincriUsuarios = $this->usuarioModel->listarUsuariosPorRol('seincri');

        require_once __DIR__ . '/../../views/jefe/reportes.php';
    }
    public function eliminar($id_reporte) {
    // Obtener datos del reporte
    $pdo = Database::getInstance()->getConnection();
    $stmt = $pdo->prepare("SELECT * FROM Reportes WHERE id_reporte = ?");
    $stmt->execute([$id_reporte]);
    $reporte = $stmt->fetch();

    if (!$reporte) {
        Session::setFlash('error', 'Reporte no encontrado.');
        header('Location: /jefe/reportes');
        exit;
    }

    // Eliminar archivo físico si existe
    if (!empty($reporte['ruta_exportacion'])) {
        $rutaArchivo = __DIR__ . '/../../public/' . $reporte['ruta_exportacion'];
        if (file_exists($rutaArchivo)) {
            unlink($rutaArchivo);
        }
    }

    // Eliminar de la BD
    $stmt = $pdo->prepare("DELETE FROM Reportes WHERE id_reporte = ?");
    $stmt->execute([$id_reporte]);

    Session::setFlash('success', 'Reporte eliminado exitosamente.');
    header('Location: /jefe/reportes');
    exit;
}

}
?>

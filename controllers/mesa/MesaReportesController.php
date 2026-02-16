<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../models/Database.php';
require_once __DIR__ . '/../../helpers/Session.php';

class MesaReportesController {
    private $incidenciaModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
    }

    public function index() {
        $estadisticas = [];
        $filtros = [];
        $resultados = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filtros = [
                'periodo' => $_POST['periodo'] ?? 'Mes',
                'tipo_delito' => $_POST['tipo_delito'] ?? '',
                'id_zona' => $_POST['id_zona'] ?? ''
            ];

            // Obtener usuario logueado
            $usuario = Session::get('usuario');
            
            // Obtener solo casos registrados por este usuario de MESA
            $resultados = $this->incidenciaModel->listarPorRegistrado($usuario['id_usuario'], $filtros);

            // ===== CALCULAR ESTADÍSTICAS =====
            $total = count($resultados);
            $por_tipo = [];
            $por_zona = [];
            $por_prioridad = ['Alta' => 0, 'Media' => 0, 'Baja' => 0];
            $pendientes_asignacion = [];
            $registros_por_dia = [];

            foreach ($resultados as $inc) {
                // Por tipo de delito
                $tipo = $inc['tipo_delito'];
                if (!isset($por_tipo[$tipo])) $por_tipo[$tipo] = 0;
                $por_tipo[$tipo]++;

                // Por zona
                if (!empty($inc['id_zona'])) {
                    $zona = $inc['id_zona'];
                    if (!isset($por_zona[$zona])) $por_zona[$zona] = 0;
                    $por_zona[$zona]++;
                }

                // Por prioridad
                $prioridad = $inc['prioridad'] ?? 'Media';
                if (isset($por_prioridad[$prioridad])) {
                    $por_prioridad[$prioridad]++;
                }

                // Pendientes de asignación
                if ($inc['estado'] === 'Pendiente') {
                    $pendientes_asignacion[] = $inc;
                }

                // Registros por día (últimos 7 días)
                $fecha = date('Y-m-d', strtotime($inc['fecha_registro']));
                if (!isset($registros_por_dia[$fecha])) {
                    $registros_por_dia[$fecha] = 0;
                }
                $registros_por_dia[$fecha]++;
            }

            // Ordenar registros por día
            krsort($registros_por_dia);

            $estadisticas = [
                'total' => $total,
                'por_tipo' => $por_tipo,
                'por_zona' => $por_zona,
                'por_prioridad' => $por_prioridad,
                'pendientes_asignacion' => count($pendientes_asignacion),
                'pendientes_lista' => array_slice($pendientes_asignacion, 0, 5), // Solo mostrar 5
                'registros_por_dia' => array_slice($registros_por_dia, 0, 7, true), // Últimos 7 días
                'promedio_diario' => $total > 0 && count($registros_por_dia) > 0 
                    ? round($total / count($registros_por_dia), 1) 
                    : 0
            ];
        }

        // Cargar listas para filtros
        $pdo = Database::getInstance()->getConnection();
        $tipos = $pdo->query("SELECT DISTINCT tipo_delito FROM DelitosClasificaciones ORDER BY tipo_delito")->fetchAll();
        $zonas = $pdo->query("SELECT * FROM Zonas ORDER BY nombre")->fetchAll();

        // Obtener nombres de zonas
        $zonasNombres = [];
        foreach ($zonas as $z) {
            $zonasNombres[$z['id_zona']] = $z['nombre'];
        }

        require_once __DIR__ . '/../../views/mesa/reportes.php';
    }
}
?>

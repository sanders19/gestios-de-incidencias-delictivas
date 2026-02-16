<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../models/Database.php';
require_once __DIR__ . '/../../helpers/Session.php';

class SeincriReportesController {
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
                'id_zona' => $_POST['id_zona'] ?? '',
                'estado' => $_POST['estado'] ?? ''
            ];

            // Obtener usuario logueado
            $usuario = Session::get('usuario');
            
            // Obtener solo casos asignados a este SEINCRI
            $resultados = $this->incidenciaModel->listarPorAsignado($usuario['id_usuario'], $filtros);

            // ===== CALCULAR ESTADÍSTICAS =====
            $total = count($resultados);
            $por_estado = [
                'Recibido' => 0,
                'Investigando' => 0,
                'Resuelto' => 0
            ];
            $por_tipo = [];
            $por_zona = [];
            $por_prioridad = ['Alta' => 0, 'Media' => 0, 'Baja' => 0];
            $casos_urgentes = [];

            foreach ($resultados as $inc) {
                // Por estado
                $estado = $inc['estado'];
                if (isset($por_estado[$estado])) {
                    $por_estado[$estado]++;
                }

                // Por tipo
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

                // Casos urgentes (Alta prioridad sin resolver)
                if ($prioridad === 'Alta' && $inc['estado'] !== 'Resuelto') {
                    $casos_urgentes[] = $inc;
                }
            }

            $estadisticas = [
                'total_asignados' => $total,
                'resueltos' => $por_estado['Resuelto'],
                'investigando' => $por_estado['Investigando'],
                'recibidos' => $por_estado['Recibido'],
                'pendientes' => $por_estado['Recibido'] + $por_estado['Investigando'],
                'tasa_resolucion' => $total > 0 ? round(($por_estado['Resuelto'] / $total) * 100, 1) : 0,
                'por_estado' => $por_estado,
                'por_tipo' => $por_tipo,
                'por_zona' => $por_zona,
                'por_prioridad' => $por_prioridad,
                'casos_urgentes' => $casos_urgentes
            ];
        }

        // Cargar listas para filtros
        $pdo = Database::getInstance()->getConnection();
        $tipos = $pdo->query("SELECT DISTINCT tipo_delito FROM DelitosClasificaciones ORDER BY tipo_delito")->fetchAll();
        $zonas = $pdo->query("SELECT * FROM Zonas ORDER BY nombre")->fetchAll();

        // Obtener nombres de zonas para mostrar
        $zonasNombres = [];
        foreach ($zonas as $z) {
            $zonasNombres[$z['id_zona']] = $z['nombre'];
        }

        require_once __DIR__ . '/../../views/seincri/reportes.php';
    }
}
?>

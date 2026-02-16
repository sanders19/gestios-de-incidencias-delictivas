<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../helpers/Session.php';

class MesaDashboardController {
    private $incidenciaModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
    }

    // ===== DASHBOARD PRINCIPAL =====
    public function index() {
        $usuario = Session::get('usuario');
        $hoy = date('Y-m-d');

        $hoyFiltros = ['fecha_desde' => $hoy, 'fecha_hasta' => $hoy];
        $incidenciasHoy = $this->incidenciaModel->listarPorRegistrado($usuario['id_usuario'], $hoyFiltros);
        $ultimos = $this->incidenciaModel->listarPorRegistrado($usuario['id_usuario'], []);
        $todas = $this->incidenciaModel->listarPorRegistrado($usuario['id_usuario'], []);
        $pendientes = array_filter($todas, fn($i) => $i['estado'] === 'Pendiente');

        require_once __DIR__ . '/../../views/mesa/dashboard.php';
    }

    // ===== VER DETALLE =====
    public function verDetalle($id) {
        $usuario = Session::get('usuario');
        $incidencia = $this->incidenciaModel->obtenerPorId($id);
        
        // Verificar que la incidencia existe y pertenece al usuario
        if (!$incidencia || $incidencia['registrado_por'] !== $usuario['id_usuario']) {
            Session::setFlash('error', 'No tienes permiso para ver esta incidencia');
            header('Location: /mesa/dashboard');
            exit;
        }

        // Obtener evidencias
        require_once __DIR__ . '/../../models/Evidencia.php';
        $evidenciaModel = new Evidencia();
        $evidencias = $evidenciaModel->listarPorIncidencia($id);

        require_once __DIR__ . '/../../views/mesa/detalle.php';
    }

    // ===== EDITAR =====
    public function editar($id) {
        $usuario = Session::get('usuario');
        $incidencia = $this->incidenciaModel->obtenerPorId($id);
        
        // Verificar permisos
        if (!$incidencia || $incidencia['registrado_por'] !== $usuario['id_usuario']) {
            Session::setFlash('error', 'No tienes permiso para editar esta incidencia');
            header('Location: /mesa/dashboard');
            exit;
        }

        // Solo permite editar si está Pendiente
        if ($incidencia['estado'] !== 'Pendiente') {
            Session::setFlash('error', 'Solo puedes editar incidencias en estado Pendiente');
            header('Location: /mesa/dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $data['id_incidencia'] = $id;
            
            try {
                $this->incidenciaModel->actualizar($data);
                Session::setFlash('success', 'Incidencia actualizada correctamente');
                header('Location: /mesa/detalle/' . $id);
                exit;
            } catch (Exception $e) {
                Session::setFlash('error', 'Error al actualizar: ' . $e->getMessage());
            }
        }

        // Cargar datos para formulario
        require_once __DIR__ . '/../../models/Database.php';
        $pdo = Database::getInstance()->getConnection();
        $delitosClasificaciones = $pdo->query("SELECT tipo_delito, clasificacion FROM DelitosClasificaciones ORDER BY tipo_delito, clasificacion")->fetchAll();
        $tiposUnicos = $pdo->query("SELECT DISTINCT tipo_delito FROM DelitosClasificaciones ORDER BY tipo_delito")->fetchAll(PDO::FETCH_COLUMN);
        $zonas = $pdo->query("SELECT * FROM Zonas ORDER BY nombre")->fetchAll();

        require_once __DIR__ . '/../../views/mesa/editar.php';
    }

    // ===== ELIMINAR =====
    public function eliminar($id) {
        $usuario = Session::get('usuario');
        $incidencia = $this->incidenciaModel->obtenerPorId($id);
        
        // Verificar permisos
        if (!$incidencia || $incidencia['registrado_por'] !== $usuario['id_usuario']) {
            Session::setFlash('error', 'No tienes permiso para eliminar esta incidencia');
            header('Location: /mesa/dashboard');
            exit;
        }

        // Solo permite eliminar si está Pendiente
        if ($incidencia['estado'] !== 'Pendiente') {
            Session::setFlash('error', 'Solo puedes eliminar incidencias en estado Pendiente');
            header('Location: /mesa/dashboard');
            exit;
        }

        // Si es POST, eliminar
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->incidenciaModel->eliminar($id);
                Session::setFlash('success', 'Incidencia eliminada correctamente');
                header('Location: /mesa/dashboard');
                exit;
            } catch (Exception $e) {
                Session::setFlash('error', 'Error al eliminar: ' . $e->getMessage());
                header('Location: /mesa/dashboard');
                exit;
            }
        }

        // Si es GET, mostrar vista de confirmación
        require_once __DIR__ . '/../../views/mesa/eliminar.php';
    }
    // ===== MIS REGISTROS (TODOS) =====
public function misRegistros() {
    $usuario = Session::get('usuario');
    
    // Obtener filtros de búsqueda
    $filtros = [];
    if (!empty($_GET['estado'])) {
        $filtros['estado'] = $_GET['estado'];
    }
    if (!empty($_GET['tipo_delito'])) {
        $filtros['tipo_delito'] = $_GET['tipo_delito'];
    }
    if (!empty($_GET['fecha_desde'])) {
        $filtros['fecha_desde'] = $_GET['fecha_desde'];
    }
    if (!empty($_GET['fecha_hasta'])) {
        $filtros['fecha_hasta'] = $_GET['fecha_hasta'];
    }
    
    $registros = $this->incidenciaModel->listarPorRegistrado($usuario['id_usuario'], $filtros);
    
    // Obtener tipos de delito para el filtro
    require_once __DIR__ . '/../../models/Database.php';
    $pdo = Database::getInstance()->getConnection();
    $tiposDelito = $pdo->query("SELECT DISTINCT tipo_delito FROM DelitosClasificaciones ORDER BY tipo_delito")->fetchAll(PDO::FETCH_COLUMN);
    
    require_once __DIR__ . '/../../views/mesa/mis-registros.php';
}

}
?>

<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../models/Evidencia.php';
require_once __DIR__ . '/../../models/Database.php';
require_once __DIR__ . '/../../helpers/Session.php';

class SeincriAtencionController {
    private $incidenciaModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
    }

    // ===== VER CASOS ASIGNADOS =====
    public function index() {
        $usuario = Session::get('usuario');
        
        // Obtener incidencias asignadas al SEINCRI logueado
        $incidencias = $this->incidenciaModel->listarPorAsignado($usuario['id_usuario'], []);
        
        require_once __DIR__ . '/../../views/seincri/atencion.php';
    }

    // ===== VER DETALLE DE CASO =====
    public function verDetalle($id) {
        $usuario = Session::get('usuario');
        $incidencia = $this->incidenciaModel->obtenerPorId($id);
        
        // Verificar que la incidencia esté asignada a este SEINCRI
        if (!$incidencia || $incidencia['asignado_a'] !== $usuario['id_usuario']) {
            Session::setFlash('error', 'No tienes permiso para ver este caso');
            header('Location: /seincri/atencion');
            exit;
        }

        // Obtener evidencias
        require_once __DIR__ . '/../../models/Evidencia.php';
        $evidenciaModel = new Evidencia();
        $evidencias = $evidenciaModel->listarPorIncidencia($id);

        // Obtener historial de estados
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("
            SELECT h.*, u.nombre_completo as cambiado_por_nombre
            FROM HistorialEstados h
            LEFT JOIN Usuarios u ON h.cambiado_por = u.id_usuario
            WHERE h.id_incidencia = ?
            ORDER BY h.fecha_cambio DESC
        ");
        $stmt->execute([$id]);
        $historial = $stmt->fetchAll();

        require_once __DIR__ . '/../../views/seincri/detalle.php';
    }

    // ===== ACTUALIZAR ESTADO =====
    public function actualizarEstado($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /seincri/atencion');
            exit;
        }

        $usuario = Session::get('usuario');
        $incidencia = $this->incidenciaModel->obtenerPorId($id);
        
        // Verificar permisos
        if (!$incidencia || $incidencia['asignado_a'] !== $usuario['id_usuario']) {
            Session::setFlash('error', 'No tienes permiso para actualizar este caso');
            header('Location: /seincri/atencion');
            exit;
        }

        $estado_nuevo = $_POST['estado_nuevo'] ?? null;
        $notas = $_POST['notas'] ?? '';

        // Validar estados permitidos
        $estados_validos = ['Recibido', 'Investigando', 'Resuelto'];
        if (!in_array($estado_nuevo, $estados_validos)) {
            Session::setFlash('error', 'Estado no válido');
            header('Location: /seincri/detalle/' . $id);
            exit;
        }

        try {
            // Actualizar estado usando el método del modelo
            $resultado = $this->incidenciaModel->actualizarEstado(
                $id, 
                $estado_nuevo, 
                $usuario['id_usuario'], 
                $notas
            );

            if ($resultado) {
                Session::setFlash('success', "Estado actualizado a: {$estado_nuevo}");
            } else {
                Session::setFlash('error', 'Error al actualizar el estado');
            }

        } catch (Exception $e) {
            Session::setFlash('error', 'Error: ' . $e->getMessage());
        }

        header('Location: /seincri/detalle/' . $id);
        exit;
    }
}
?>

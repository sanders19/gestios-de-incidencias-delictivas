<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../models/Database.php';
require_once __DIR__ . '/../../helpers/Session.php';

class JefeAsignacionController {
    private $incidenciaModel;
    private $usuarioModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
        $this->usuarioModel = new Usuario();
    }

    // ===== VER INCIDENCIAS PENDIENTES =====
    public function index() {
        // Obtener incidencias pendientes (sin asignar)
        $pendientes = $this->incidenciaModel->listarTodas(['estado' => 'Pendiente']);
        
        // Obtener usuarios SEINCRI disponibles
        $seincris = $this->usuarioModel->listarPorRol('seincri');
        
        require_once __DIR__ . '/../../views/jefe/asignacion.php';
    }

    // ===== ASIGNAR INCIDENCIA A SEINCRI =====
    public function asignar($id_incidencia) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Session::setFlash('error', 'Método no permitido');
            header('Location: /jefe/asignacion');
            exit;
        }

        $usuario = Session::get('usuario');
        $asignado_a = $_POST['asignado_a'] ?? null;

        // Validar que se seleccionó un SEINCRI
        if (!$asignado_a) {
            Session::setFlash('error', 'Debe seleccionar un investigador SEINCRI');
            header('Location: /jefe/asignacion');
            exit;
        }

        try {
            $pdo = Database::getInstance()->getConnection();
            $pdo->beginTransaction();

            // 1. Actualizar incidencia: asignar + cambiar estado
            $stmt = $pdo->prepare("
                UPDATE Incidencias 
                SET asignado_a = ?, estado = 'Recibido' 
                WHERE id_incidencia = ?
            ");
            $stmt->execute([$asignado_a, $id_incidencia]);

            // 2. Registrar en tabla Asignaciones (historial)
            $stmt = $pdo->prepare("
                INSERT INTO Asignaciones (id_incidencia, asignado_a, asignado_por)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$id_incidencia, $asignado_a, $usuario['id_usuario']]);

            // 3. Registrar cambio de estado en HistorialEstados
            $stmt = $pdo->prepare("
                INSERT INTO HistorialEstados (id_incidencia, estado_anterior, estado_nuevo, cambiado_por, notas)
                VALUES (?, 'Pendiente', 'Recibido', ?, ?)
            ");
            $notas = "Caso asignado a investigador SEINCRI: {$asignado_a}";
            $stmt->execute([$id_incidencia, $usuario['id_usuario'], $notas]);

            $pdo->commit();
            
            Session::setFlash('success', "Incidencia #{$id_incidencia} asignada correctamente");
            header('Location: /jefe/asignacion');
            exit;

        } catch (Exception $e) {
            $pdo->rollback();
            Session::setFlash('error', 'Error al asignar: ' . $e->getMessage());
            header('Location: /jefe/asignacion');
            exit;
        }
    }

    // ===== VER HISTORIAL DE ASIGNACIONES =====
        // ===== VER HISTORIAL DE ASIGNACIONES =====
    public function historial() {
        $pdo = Database::getInstance()->getConnection();
        
        $stmt = $pdo->query("
            SELECT 
                a.id_asignacion,
                a.id_incidencia, 
                a.asignado_en as fecha_asignacion,
                i.tipo_delito, 
                i.estado, 
                i.prioridad, 
                i.direccion_incidencia,
                p.nombre_completo as denunciante_nombre,
                '' as apellido_paterno,
                '' as apellido_materno,
                u1.nombre_completo as asignado_por_nombre,
                u2.nombre_completo as seincri_nombre
            FROM Asignaciones a
            INNER JOIN Incidencias i ON a.id_incidencia = i.id_incidencia
            INNER JOIN Personas p ON i.id_denunciante = p.id_persona
            INNER JOIN Usuarios u1 ON a.asignado_por = u1.id_usuario
            INNER JOIN Usuarios u2 ON a.asignado_a = u2.id_usuario
            ORDER BY a.asignado_en DESC
            LIMIT 100
        ");
        $historial = $stmt->fetchAll();
        
        require_once __DIR__ . '/../../views/jefe/historial-asignaciones.php';
    }

}
?>

<?php
require_once 'Database.php';

class Evidencia {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function guardar($id_incidencia, $tipo_archivo, $ruta_archivo, $subido_por) {
        $stmt = $this->pdo->prepare("
            INSERT INTO Evidencias (id_incidencia, tipo_archivo, ruta_archivo, subido_por)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$id_incidencia, $tipo_archivo, $ruta_archivo, $subido_por]);
    }

    // ✅ RENOMBRADO: obtenerPorIncidencia → listarPorIncidencia
    public function listarPorIncidencia($id_incidencia) {
        $stmt = $this->pdo->prepare("
            SELECT e.*, u.nombre_completo as subido_por_nombre
            FROM Evidencias e
            LEFT JOIN Usuarios u ON e.subido_por = u.id_usuario
            WHERE e.id_incidencia = ?
            ORDER BY e.subido_en DESC
        ");
        $stmt->execute([$id_incidencia]);
        return $stmt->fetchAll();
    }
}
?>

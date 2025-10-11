<?php
require_once 'Database.php';

class Asignacion {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function asignar($id_incidencia, $asignado_a, $asignado_por) {
        $this->pdo->beginTransaction();
        try {
            // Insertar asignación
            $stmt = $this->pdo->prepare("
                INSERT INTO Asignaciones (id_incidencia, asignado_a, asignado_por)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$id_incidencia, $asignado_a, $asignado_por]);

            // Actualizar incidencia
            $stmt = $this->pdo->prepare("UPDATE Incidencias SET asignado_a = ?, estado = 'Recibido' WHERE id_incidencia = ?");
            $stmt->execute([$asignado_a, $id_incidencia]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollback();
            return false;
        }
    }

    public function listarAsignaciones() {
        $sql = "
            SELECT a.*, i.tipo_delito, i.direccion_incidencia, i.fecha_registro,
                   u1.nombre_completo as asignado_a_nombre,
                   u2.nombre_completo as asignado_por_nombre
            FROM Asignaciones a
            JOIN Incidencias i ON a.id_incidencia = i.id_incidencia
            JOIN Usuarios u1 ON a.asignado_a = u1.id_usuario
            JOIN Usuarios u2 ON a.asignado_por = u2.id_usuario
            ORDER BY a.asignado_en DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>
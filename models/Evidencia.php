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

    public function obtenerPorIncidencia($id_incidencia) {
        $stmt = $this->pdo->prepare("SELECT * FROM Evidencias WHERE id_incidencia = ?");
        $stmt->execute([$id_incidencia]);
        return $stmt->fetchAll();
    }
}
?>
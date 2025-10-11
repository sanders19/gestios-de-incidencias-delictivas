<?php
require_once 'Database.php';

class Reporte {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function crear($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO Reportes (
                generado_por, periodo, tipo_delito, id_zona, id_asignado_a, id_registrado_por,
                datos_reporte, ruta_exportacion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['generado_por'],
            $data['periodo'],
            $data['tipo_delito'] ?: null,
            $data['id_zona'] ?: null,
            $data['id_asignado_a'] ?: null,
            $data['id_registrado_por'] ?: null,
            json_encode($data['datos_reporte']),
            $data['ruta_exportacion']
        ]);
    }

    public function listarPorGenerado($generado_por) {
        $stmt = $this->pdo->prepare("SELECT * FROM Reportes WHERE generado_por = ? ORDER BY generado_en DESC");
        $stmt->execute([$generado_por]);
        return $stmt->fetchAll();
    }
}
?>
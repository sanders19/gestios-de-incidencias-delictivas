<?php
require_once 'Database.php';

class Incidencia {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function generarId() {
        $anio = date('Y');
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM Incidencias WHERE id_incidencia LIKE ?");
        $stmt->execute(["INC-{$anio}-%"]);
        $count = $stmt->fetchColumn() + 1;
        return "INC-{$anio}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function crear($data) {
        $this->pdo->beginTransaction();
        try {
            // Insertar denunciante
            $stmt = $this->pdo->prepare("
                INSERT INTO Personas (nombre_completo, sexo, dni, direccion, telefono)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['denunciante_nombre'],
                $data['denunciante_sexo'],
                $data['denunciante_dni'] ?: null,
                $data['denunciante_direccion'],
                $data['denunciante_telefono']
            ]);
            $id_denunciante = $this->pdo->lastInsertId();

            // Insertar agredido (si aplica)
            $id_agredido = null;
            if ($data['tipo_agredido'] === 'otra_persona') {
                $stmt = $this->pdo->prepare("
                    INSERT INTO Personas (nombre_completo, sexo, dni, direccion, telefono)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $data['agredido_nombre'],
                    $data['agredido_sexo'],
                    $data['agredido_dni'] ?: null,
                    $data['agredido_direccion'] ?? null,
                    $data['agredido_telefono'] ?? null
                ]);
                $id_agredido = $this->pdo->lastInsertId();
            }

            // Insertar agresor (opcional)
            $id_agresor = null;
            if (!empty($data['agresor_nombre'])) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO Personas (nombre_completo, sexo, dni, direccion, telefono)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $data['agresor_nombre'],
                    $data['agresor_sexo'],
                    $data['agresor_dni'] ?: null,
                    $data['agresor_direccion'] ?? null,
                    $data['agresor_telefono'] ?? null
                ]);
                $id_agresor = $this->pdo->lastInsertId();
            }

            // Insertar incidencia CON GEOLOCALIZACIÓN
            $id_incidencia = $this->generarId();
            $stmt = $this->pdo->prepare("
                INSERT INTO Incidencias (
                    id_incidencia, tipo_delito, clasificacion_delito, descripcion,
                    direccion_incidencia, latitud, longitud, geo_confidence,
                    geolocated_by, geolocated_at, estado, prioridad,
                    id_denunciante, id_agredido, id_agresor, tipo_agredido,
                    registrado_por, id_zona
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $id_incidencia,
                $data['tipo_delito'],
                $data['clasificacion_delito'],
                $data['descripcion'],
                $data['direccion_incidencia'],
                $data['latitud'],
                $data['longitud'],
                $data['geo_confidence'],
                $data['geolocated_by'],
                $data['geolocated_at'],
                'Pendiente',
                $data['prioridad'],
                $id_denunciante,
                $id_agredido,
                $id_agresor,
                $data['tipo_agredido'],
                $data['registrado_por'],
                $data['id_zona']
            ]);

            $this->pdo->commit();
            return $id_incidencia;
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }

    public function obtenerPorId($id_incidencia) {
        $stmt = $this->pdo->prepare("
            SELECT i.*, 
                d.nombre_completo as denunciante_nombre, d.dni as denunciante_dni,
                a.nombre_completo as agredido_nombre,
                ag.nombre_completo as agresor_nombre,
                z.nombre as zona_nombre
            FROM Incidencias i
            LEFT JOIN Personas d ON i.id_denunciante = d.id_persona
            LEFT JOIN Personas a ON i.id_agredido = a.id_persona
            LEFT JOIN Personas ag ON i.id_agresor = ag.id_persona
            LEFT JOIN Zonas z ON i.id_zona = z.id_zona
            WHERE i.id_incidencia = ?
        ");
        $stmt->execute([$id_incidencia]);
        return $stmt->fetch();
    }

    public function listarPorRegistrado($registrado_por, $filtros = []) {
        $sql = "
            SELECT i.id_incidencia, i.tipo_delito, i.fecha_registro, i.direccion_incidencia, i.estado, i.prioridad
            FROM Incidencias i
            WHERE i.registrado_por = :registrado_por
        ";
        $params = ['registrado_por' => $registrado_por];

        if (!empty($filtros['tipo_delito'])) {
            $sql .= " AND i.tipo_delito = :tipo_delito";
            $params['tipo_delito'] = $filtros['tipo_delito'];
        }
        if (!empty($filtros['estado'])) {
            $sql .= " AND i.estado = :estado";
            $params['estado'] = $filtros['estado'];
        }
        if (!empty($filtros['id_zona'])) {
            $sql .= " AND i.id_zona = :id_zona";
            $params['id_zona'] = $filtros['id_zona'];
        }
        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND DATE(i.fecha_registro) >= :fecha_desde";
            $params['fecha_desde'] = $filtros['fecha_desde'];
        }
        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND DATE(i.fecha_registro) <= :fecha_hasta";
            $params['fecha_hasta'] = $filtros['fecha_hasta'];
        }

        $sql .= " ORDER BY i.fecha_registro DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function listarPorAsignado($asignado_a, $filtros = []) {
        $sql = "
            SELECT i.id_incidencia, i.tipo_delito, i.fecha_registro, i.direccion_incidencia, i.estado, i.prioridad
            FROM Incidencias i
            WHERE i.asignado_a = :asignado_a
        ";
        $params = ['asignado_a' => $asignado_a];

        if (!empty($filtros['tipo_delito'])) {
            $sql .= " AND i.tipo_delito = :tipo_delito";
            $params['tipo_delito'] = $filtros['tipo_delito'];
        }
        if (!empty($filtros['estado'])) {
            $sql .= " AND i.estado = :estado";
            $params['estado'] = $filtros['estado'];
        }
        if (!empty($filtros['id_zona'])) {
            $sql .= " AND i.id_zona = :id_zona";
            $params['id_zona'] = $filtros['id_zona'];
        }
        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND DATE(i.fecha_registro) >= :fecha_desde";
            $params['fecha_desde'] = $filtros['fecha_desde'];
        }
        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND DATE(i.fecha_registro) <= :fecha_hasta";
            $params['fecha_hasta'] = $filtros['fecha_hasta'];
        }

        $sql .= " ORDER BY i.fecha_registro DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function listarTodas($filtros = []) {
        $sql = "SELECT i.*, u1.nombre_completo as registrado_por_nombre, u2.nombre_completo as asignado_a_nombre
                FROM Incidencias i
                LEFT JOIN Usuarios u1 ON i.registrado_por = u1.id_usuario
                LEFT JOIN Usuarios u2 ON i.asignado_a = u2.id_usuario
                WHERE 1=1";
        $params = [];

        if (!empty($filtros['tipo_delito'])) {
            $sql .= " AND i.tipo_delito = :tipo_delito";
            $params['tipo_delito'] = $filtros['tipo_delito'];
        }
        if (!empty($filtros['estado'])) {
            $sql .= " AND i.estado = :estado";
            $params['estado'] = $filtros['estado'];
        }
        if (!empty($filtros['id_zona'])) {
            $sql .= " AND i.id_zona = :id_zona";
            $params['id_zona'] = $filtros['id_zona'];
        }
        if (!empty($filtros['asignado_a'])) {
            $sql .= " AND i.asignado_a = :asignado_a";
            $params['asignado_a'] = $filtros['asignado_a'];
        }
        if (!empty($filtros['registrado_por'])) {
            $sql .= " AND i.registrado_por = :registrado_por";
            $params['registrado_por'] = $filtros['registrado_por'];
        }
        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND DATE(i.fecha_registro) >= :fecha_desde";
            $params['fecha_desde'] = $filtros['fecha_desde'];
        }
        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND DATE(i.fecha_registro) <= :fecha_hasta";
            $params['fecha_hasta'] = $filtros['fecha_hasta'];
        }

        $sql .= " ORDER BY i.fecha_registro DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function actualizarEstado($id_incidencia, $estado_nuevo, $cambiado_por, $notas = '') {
        $this->pdo->beginTransaction();
        try {
            // Obtener estado actual
            $stmt = $this->pdo->prepare("SELECT estado FROM Incidencias WHERE id_incidencia = ?");
            $stmt->execute([$id_incidencia]);
            $estado_anterior = $stmt->fetchColumn();

            // Actualizar incidencia
            $stmt = $this->pdo->prepare("UPDATE Incidencias SET estado = ? WHERE id_incidencia = ?");
            $stmt->execute([$estado_nuevo, $id_incidencia]);

            // Registrar en historial
            $stmt = $this->pdo->prepare("
                INSERT INTO HistorialEstados (id_incidencia, estado_anterior, estado_nuevo, cambiado_por, notas)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$id_incidencia, $estado_anterior, $estado_nuevo, $cambiado_por, $notas]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollback();
            return false;
        }
    }
    public function actualizar($data) {
    $stmt = $this->pdo->prepare("
        UPDATE Incidencias 
        SET tipo_delito = ?, clasificacion_delito = ?, descripcion = ?,
            direccion_incidencia = ?, prioridad = ?, id_zona = ?,
            latitud = ?, longitud = ?, geo_confidence = ?
        WHERE id_incidencia = ?
    ");
    return $stmt->execute([
        $data['tipo_delito'],
        $data['clasificacion_delito'],
        $data['descripcion'],
        $data['direccion_incidencia'],
        $data['prioridad'],
        $data['id_zona'] ?? null,
        $data['latitud'] ?? null,
        $data['longitud'] ?? null,
        $data['geo_confidence'] ?? null,
        $data['id_incidencia']
    ]);
}

public function eliminar($id_incidencia) {
    $stmt = $this->pdo->prepare("DELETE FROM Incidencias WHERE id_incidencia = ?");
    return $stmt->execute([$id_incidencia]);
}

}
?>

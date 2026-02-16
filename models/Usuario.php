<?php
require_once 'Database.php';

class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function login($nombre_usuario, $contrasena) {
        error_log("LOG Usuario.php: Intentando login para usuario: " . $nombre_usuario);
        
        $stmt = $this->pdo->prepare("SELECT * FROM Usuarios WHERE nombre_usuario = ?");
        $stmt->execute([$nombre_usuario]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            error_log("LOG Usuario.php: Usuario encontrado en BD. Verificando contraseña...");
            if (password_verify($contrasena, $usuario['contrasena_hash'])) {
                error_log("LOG Usuario.php: ¡Contraseña correcta! Rol del usuario: " . $usuario['rol']);
                return $usuario;
            } else {
                error_log("LOG Usuario.php: Contraseña INCORRECTA para usuario: " . $nombre_usuario);
                return false;
            }
        } else {
            error_log("LOG Usuario.php: Usuario NO ENCONTRADO: " . $nombre_usuario);
            return false;
        }
    }

    public function actualizarUltimoAcceso($id_usuario) {
        $stmt = $this->pdo->prepare("UPDATE Usuarios SET ultimo_acceso = NOW() WHERE id_usuario = ?");
        return $stmt->execute([$id_usuario]);
    }

    public function cambiarContrasena($id_usuario, $nueva_contrasena) {
        $hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE Usuarios SET contrasena_hash = ?, es_primer_inicio = FALSE WHERE id_usuario = ?");
        return $stmt->execute([$hash, $id_usuario]);
    }

    public function obtenerPorId($id_usuario) {
        $stmt = $this->pdo->prepare("SELECT * FROM Usuarios WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch();
    }

    public function crearUsuario($id_usuario, $nombre_usuario, $contrasena, $rol, $nombre_completo, $comisaria) {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("
            INSERT INTO Usuarios (id_usuario, nombre_usuario, contrasena_hash, rol, nombre_completo, comisaria)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$id_usuario, $nombre_usuario, $hash, $rol, $nombre_completo, $comisaria]);
    }

    public function listarUsuariosPorRol($rol) {
        $stmt = $this->pdo->prepare("SELECT id_usuario, nombre_usuario, nombre_completo, rol, comisaria FROM Usuarios WHERE rol = ? ORDER BY id_usuario ASC");
        $stmt->execute([$rol]);
        return $stmt->fetchAll();
    }

    // ===== NUEVO: Listar por rol (alias simplificado) =====
    public function listarPorRol($rol) {
        $stmt = $this->pdo->prepare("
            SELECT id_usuario, nombre_completo, nombre_usuario, rol, comisaria
            FROM Usuarios
            WHERE rol = ?
            ORDER BY nombre_completo
        ");
        $stmt->execute([$rol]);
        return $stmt->fetchAll();
    }

    public function listarTodosUsuarios() {
        $stmt = $this->pdo->prepare("SELECT id_usuario, nombre_usuario, nombre_completo, rol, comisaria FROM Usuarios ORDER BY rol ASC, id_usuario ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateUsuario($id_usuario, $nombre_usuario, $nombre_completo, $rol, $comisaria) {
        $stmt = $this->pdo->prepare("UPDATE Usuarios SET nombre_usuario = ?, nombre_completo = ?, rol = ?, comisaria = ? WHERE id_usuario = ?");
        return $stmt->execute([$nombre_usuario, $nombre_completo, $rol, $comisaria, $id_usuario]);
    }

    public function deleteUsuario($id_usuario) {
        $stmt = $this->pdo->prepare("DELETE FROM Usuarios WHERE id_usuario = ?");
        return $stmt->execute([$id_usuario]);
    }

    // Genera el siguiente id de usuario según el rol (ej. MESA-001)
    public function generarSiguienteId($rol) {
        $map = [
            'mesa' => 'MESA',
            'seincri' => 'SEINCRI',
            'jefe' => 'JEFE'
        ];

        $prefix = $map[$rol] ?? strtoupper($rol);

        $stmt = $this->pdo->prepare("SELECT id_usuario FROM Usuarios WHERE id_usuario LIKE ? ORDER BY id_usuario DESC LIMIT 1");
        $like = $prefix . '-%';
        $stmt->execute([$like]);
        $row = $stmt->fetch();

        if ($row && isset($row['id_usuario'])) {
            $parts = explode('-', $row['id_usuario']);
            $number = intval(end($parts));
            $next = $number + 1;
        } else {
            $next = 1;
        }

        $id = sprintf('%s-%03d', $prefix, $next);
        error_log("LOG Usuario.php: Generando siguiente id para rol {$rol}: {$id}");
        return $id;
    }
}
?>

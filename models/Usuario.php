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
        $stmt = $this->pdo->prepare("SELECT id_usuario, nombre_completo FROM Usuarios WHERE rol = ?");
        $stmt->execute([$rol]);
        return $stmt->fetchAll();
    }
}
?>
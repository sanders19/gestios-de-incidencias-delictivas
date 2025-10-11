<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../models/Evidencia.php';
require_once __DIR__ . '/../../helpers/Session.php';

class SeincriAtencionController {
    private $incidenciaModel;
    private $evidenciaModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
        $this->evidenciaModel = new Evidencia();
    }

    public function index() {
        $usuario = Session::get('usuario');
        $incidencias = $this->incidenciaModel->listarPorAsignado($usuario['id_usuario']);
        require_once __DIR__ . '/../../views/seincri/atencion.php';
    }

    public function actualizarEstado($id_incidencia) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estado = $_POST['estado'] ?? '';
            $notas = $_POST['notas'] ?? '';

            if (in_array($estado, ['Recibido', 'Investigando', 'Resuelto'])) {
                $usuario = Session::get('usuario');
                if ($this->incidenciaModel->actualizarEstado($id_incidencia, $estado, $usuario['id_usuario'], $notas)) {
                    Session::setFlash('success', 'Estado actualizado correctamente');
                } else {
                    Session::setFlash('error', 'Error al actualizar estado');
                }
            } else {
                Session::setFlash('error', 'Estado inválido');
            }
        }

        $incidencia = $this->incidenciaModel->obtenerPorId($id_incidencia);
        $evidencias = $this->evidenciaModel->obtenerPorIncidencia($id_incidencia);
        require_once __DIR__ . '/../../views/seincri/detalle.php';
    }
}
?>
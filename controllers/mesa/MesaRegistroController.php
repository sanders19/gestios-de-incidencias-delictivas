<?php
require_once __DIR__ . '/../../models/Incidencia.php';
require_once __DIR__ . '/../../models/Evidencia.php';
require_once __DIR__ . '/../../helpers/Uploader.php';
require_once __DIR__ . '/../../helpers/Validator.php';
require_once __DIR__ . '/../../helpers/Session.php';

class MesaRegistroController {
    private $incidenciaModel;
    private $evidenciaModel;

    public function __construct() {
        $this->incidenciaModel = new Incidencia();
        $this->evidenciaModel = new Evidencia();
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $data['registrado_por'] = Session::get('usuario')['id_usuario'];

            try {
                $id_incidencia = $this->incidenciaModel->crear($data);

                // Subir evidencias
                if (!empty($_FILES['evidencias']['name'][0])) {
                    $uploader = new Uploader();
                    $rutas = $uploader->subirMultiples($_FILES['evidencias'], 'evidencias/');
                    foreach ($rutas as $ruta) {
                        $tipo = pathinfo($ruta, PATHINFO_EXTENSION);
                        $tipo_archivo = in_array($tipo, ['jpg','jpeg','png']) ? 'foto' : (in_array($tipo, ['mp4','avi']) ? 'video' : 'audio');
                        $this->evidenciaModel->guardar($id_incidencia, $tipo_archivo, $ruta, $data['registrado_por']);
                    }
                }

                Session::setFlash('success', "Incidencia registrada con ID: {$id_incidencia}");
                header("Location: /mesa/dashboard");
                exit;
            } catch (Exception $e) {
                Session::setFlash('error', 'Error al registrar incidencia: ' . $e->getMessage());
            }
        }

        // Cargar listas para formulario
        $pdo = Database::getInstance()->getConnection();
        $delitos = $pdo->query("SELECT DISTINCT tipo_delito FROM DelitosClasificaciones ORDER BY tipo_delito")->fetchAll();
        $zonas = $pdo->query("SELECT * FROM Zonas ORDER BY nombre")->fetchAll();

        require_once __DIR__ . '/../../views/mesa/registro.php';
    }
}
?>
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
            $data['id_zona'] = isset($_POST['id_zona']) && $_POST['id_zona'] !== '' ? $_POST['id_zona'] : null;

            // ===== PROCESAR GEOLOCALIZACIÓN =====
            $latitud = isset($_POST['latitud']) && $_POST['latitud'] !== '' ? floatval($_POST['latitud']) : null;
            $longitud = isset($_POST['longitud']) && $_POST['longitud'] !== '' ? floatval($_POST['longitud']) : null;
            $geo_confidence = isset($_POST['geo_confidence']) && $_POST['geo_confidence'] !== '' ? $_POST['geo_confidence'] : null;

            // Validar rangos de coordenadas para Perú
            if ($latitud !== null && $longitud !== null) {
                if ($latitud < -18.5 || $latitud > -0.0 || $longitud < -81.5 || $longitud > -68.5) {
                    Session::setFlash('error', 'Coordenadas fuera del rango válido para Perú');
                    header("Location: /mesa/registro");
                    exit;
                }

                // Añadir metadata de geolocalización
                $data['latitud'] = $latitud;
                $data['longitud'] = $longitud;
                $data['geo_confidence'] = $geo_confidence;
                $data['geolocated_by'] = $data['registrado_por'];
                $data['geolocated_at'] = date('Y-m-d H:i:s');
            } else {
                $data['latitud'] = null;
                $data['longitud'] = null;
                $data['geo_confidence'] = null;
                $data['geolocated_by'] = null;
                $data['geolocated_at'] = null;
            }

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
        $delitosClasificaciones = $pdo->query("SELECT tipo_delito, clasificacion FROM DelitosClasificaciones ORDER BY tipo_delito, clasificacion")->fetchAll();
        $tiposUnicos = $pdo->query("SELECT DISTINCT tipo_delito FROM DelitosClasificaciones ORDER BY tipo_delito")->fetchAll(PDO::FETCH_COLUMN);
        $zonas = $pdo->query("SELECT * FROM Zonas ORDER BY nombre")->fetchAll();

        require_once __DIR__ . '/../../views/mesa/registro.php';
    }
}
?>

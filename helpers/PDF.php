<?php
// Asumimos que usas FPDF (debes tener fpdf186/ en tu proyecto o usar Composer)
require_once __DIR__ . '/fpdf/fpdf.php'; // Ajusta la ruta según tu instalación

class PDF extends FPDF {
    private $title;

    public function __construct($title = 'Reporte Policial') {
        parent::__construct();
        $this->title = $title;
    }

    public function Header() {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, $this->title, 0, 1, 'C');
        $this->Ln(5);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 8, 'Sistema Policial - Huancavelica', 0, 1, 'C');
        $this->Ln(5);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . ' / {nb}', 0, 0, 'C');
    }

    public function generarReporte($data, $ruta) {
        $this->AliasNbPages();
        $this->AddPage();

        // Datos generales
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Resumen del Reporte', 0, 1);
        $this->Ln(4);

        $this->SetFont('Arial', '', 10);
        $this->Cell(50, 8, 'Periodo:', 0);
        $this->Cell(0, 8, $data['periodo'] ?? 'N/A', 0, 1);
        $this->Cell(50, 8, 'Tipo de delito:', 0);
        $this->Cell(0, 8, $data['tipo_delito'] ?? 'Todos', 0, 1);
        $this->Cell(50, 8, 'Zona:', 0);
        $this->Cell(0, 8, $data['zona_nombre'] ?? 'Todas', 0, 1);
        $this->Ln(6);

        // Estadísticas
        $stats = json_decode($data['datos_reporte'], true) ?? [];
        if (!empty($stats)) {
            $this->SetFont('Arial', 'B', 11);
            $this->Cell(0, 10, 'Estadísticas', 0, 1);
            $this->Ln(2);
            $this->SetFont('Arial', '', 10);

            foreach ($stats as $key => $value) {
                $label = str_replace('_', ' ', ucfirst($key));
                $this->Cell(60, 8, $label . ':', 0);
                $this->Cell(0, 8, (string)$value, 0, 1);
            }
        }

        $pdfDir = dirname($ruta);
        if (!is_dir($pdfDir)) {
            mkdir($pdfDir, 0755, true);
        }

        $this->Output('F', $ruta);
        return $ruta;
    }
}
?>
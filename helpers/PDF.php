<?php
require_once __DIR__ . '/fpdf/fpdf.php';

class PDF extends FPDF {
    private $title;

    public function __construct($title = 'Reporte Policial') {
        parent::__construct();
        $this->title = $title;
    }

    public function Header() {
        // Logo (si existe)
        $logoPath = __DIR__ . '/../public/images/logo-policia.png';
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 10, 6, 30);
        }
        
        // Título
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, $this->sanitize($this->title), 0, 1, 'C');
        
        // Subtítulo
        $this->SetFont('Arial', 'I', 11);
        $this->Cell(0, 8, $this->sanitize('Sistema de Gestion de Incidencias'), 0, 1, 'C');
        $this->Cell(0, 8, $this->sanitize('Comisaria PNP Huancavelica Centro'), 0, 1, 'C');
        
        $this->Ln(3);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, $this->sanitize('Pagina ') . $this->PageNo() . ' / {nb}', 0, 0, 'C');
    }

    // Método para sanitizar texto (reemplaza utf8_decode)
    private function sanitize($text) {
        // Reemplazar caracteres especiales españoles
        $replacements = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ñ' => 'n', 'Ñ' => 'N',
            'ü' => 'u', 'Ü' => 'U',
            '°' => '', '¿' => '', '¡' => ''
        ];
        return str_replace(array_keys($replacements), array_values($replacements), $text);
    }

    public function generarReporte($data, $ruta) {
        $this->AliasNbPages();
        $this->AddPage();

        // ===== RESUMEN EJECUTIVO =====
        $this->SetFont('Arial', 'B', 14);
        $this->SetFillColor(40, 167, 69);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 10, $this->sanitize('RESUMEN EJECUTIVO'), 0, 1, 'C', true);
        $this->SetTextColor(0, 0, 0);
        $this->Ln(5);

        // Información del reporte
        $this->SetFont('Arial', '', 10);
        $this->Cell(50, 7, $this->sanitize('Periodo:'), 0, 0);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 7, $this->sanitize($data['periodo'] ?? 'N/A'), 0, 1);
        
        $this->SetFont('Arial', '', 10);
        $this->Cell(50, 7, $this->sanitize('Fecha de generacion:'), 0, 0);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 7, $this->sanitize($data['fecha_generacion'] ?? date('d/m/Y H:i')), 0, 1);
        
        $this->SetFont('Arial', '', 10);
        $this->Cell(50, 7, $this->sanitize('Generado por:'), 0, 0);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 7, $this->sanitize($data['generado_por'] ?? 'Sistema'), 0, 1);
        $this->Ln(3);

        // Filtros aplicados
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 8, $this->sanitize('Filtros Aplicados:'), 0, 1);
        $this->SetFont('Arial', '', 10);
        $this->Cell(10, 6, '', 0);
        $this->Cell(50, 6, $this->sanitize('Tipo de delito:'), 0, 0);
        $this->Cell(0, 6, $this->sanitize($data['tipo_delito'] ?? 'Todos'), 0, 1);
        
        $this->Cell(10, 6, '', 0);
        $this->Cell(50, 6, $this->sanitize('Zona:'), 0, 0);
        $this->Cell(0, 6, $this->sanitize($data['zona_nombre'] ?? 'Todas'), 0, 1);
        
        $this->Cell(10, 6, '', 0);
        $this->Cell(50, 6, $this->sanitize('Asignado a:'), 0, 0);
        $this->Cell(0, 6, $this->sanitize($data['asignado_a_nombre'] ?? 'Todos'), 0, 1);
        
        $this->Cell(10, 6, '', 0);
        $this->Cell(50, 6, $this->sanitize('Registrado por:'), 0, 0);
        $this->Cell(0, 6, $this->sanitize($data['registrado_por_nombre'] ?? 'Todos'), 0, 1);
        $this->Ln(8);

        $stats = $data['datos_reporte'] ?? [];
        
        if (empty($stats['total_incidencias'])) {
            $this->SetFont('Arial', 'I', 11);
            $this->Cell(0, 10, $this->sanitize('No se encontraron incidencias con los filtros aplicados.'), 0, 1, 'C');
            $this->Output('F', $ruta);
            return $ruta;
        }

        // ===== INDICADORES PRINCIPALES =====
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(230, 230, 230);
        $this->Cell(0, 9, $this->sanitize('1. INDICADORES PRINCIPALES'), 0, 1, 'L', true);
        $this->Ln(3);

        $total = $stats['total_incidencias'];
        $resueltos = $stats['por_estado']['Resuelto'] ?? 0;
        $tasa = $total > 0 ? round(($resueltos / $total) * 100, 1) : 0;

        $this->SetFont('Arial', 'B', 11);
        $this->Cell(90, 8, $this->sanitize('Total de Incidencias:'), 1, 0, 'L');
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 8, (string)$total, 1, 1, 'C');
        
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(90, 8, $this->sanitize('Casos Resueltos:'), 1, 0, 'L');
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 8, (string)$resueltos, 1, 1, 'C');
        
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(90, 8, $this->sanitize('Tasa de Resolucion:'), 1, 0, 'L');
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 8, $tasa . '%', 1, 1, 'C');
        $this->Ln(6);

        // ===== POR ESTADO =====
        if (!empty($stats['por_estado'])) {
            $this->SetFont('Arial', 'B', 12);
            $this->SetFillColor(230, 230, 230);
            $this->Cell(0, 9, $this->sanitize('2. DISTRIBUCION POR ESTADO'), 0, 1, 'L', true);
            $this->Ln(2);

            $this->SetFont('Arial', 'B', 10);
            $this->Cell(120, 7, $this->sanitize('Estado'), 1, 0, 'C', true);
            $this->Cell(35, 7, $this->sanitize('Cantidad'), 1, 0, 'C', true);
            $this->Cell(0, 7, $this->sanitize('Porcentaje'), 1, 1, 'C', true);

            $this->SetFont('Arial', '', 10);
            foreach ($stats['por_estado'] as $estado => $cantidad) {
                $porcentaje = round(($cantidad / $total) * 100, 1);
                $this->Cell(120, 6, $this->sanitize($estado), 1);
                $this->Cell(35, 6, (string)$cantidad, 1, 0, 'C');
                $this->Cell(0, 6, $porcentaje . '%', 1, 1, 'C');
            }
            $this->Ln(6);
        }

        // ===== POR TIPO DE DELITO =====
        if (!empty($stats['por_tipo'])) {
            $this->SetFont('Arial', 'B', 12);
            $this->SetFillColor(230, 230, 230);
            $this->Cell(0, 9, $this->sanitize('3. DISTRIBUCION POR TIPO DE DELITO'), 0, 1, 'L', true);
            $this->Ln(2);

            $this->SetFont('Arial', 'B', 10);
            $this->Cell(120, 7, $this->sanitize('Tipo de Delito'), 1, 0, 'C', true);
            $this->Cell(35, 7, $this->sanitize('Cantidad'), 1, 0, 'C', true);
            $this->Cell(0, 7, $this->sanitize('Porcentaje'), 1, 1, 'C', true);

            $this->SetFont('Arial', '', 9);
            arsort($stats['por_tipo']);
            $contador = 0;
            foreach ($stats['por_tipo'] as $tipo => $cantidad) {
                if ($contador >= 15) break;
                $porcentaje = round(($cantidad / $total) * 100, 1);
                $this->Cell(120, 6, $this->sanitize(substr($tipo, 0, 45)), 1);
                $this->Cell(35, 6, (string)$cantidad, 1, 0, 'C');
                $this->Cell(0, 6, $porcentaje . '%', 1, 1, 'C');
                $contador++;
            }
            $this->Ln(6);
        }

        // ===== POR ZONA =====
        if (!empty($stats['por_zona'])) {
            $this->AddPage();
            $this->SetFont('Arial', 'B', 12);
            $this->SetFillColor(230, 230, 230);
            $this->Cell(0, 9, $this->sanitize('4. DISTRIBUCION POR ZONA'), 0, 1, 'L', true);
            $this->Ln(2);

            $this->SetFont('Arial', 'B', 10);
            $this->Cell(120, 7, $this->sanitize('Zona'), 1, 0, 'C', true);
            $this->Cell(35, 7, $this->sanitize('Cantidad'), 1, 0, 'C', true);
            $this->Cell(0, 7, $this->sanitize('Porcentaje'), 1, 1, 'C', true);

            $this->SetFont('Arial', '', 10);
            arsort($stats['por_zona']);
            foreach ($stats['por_zona'] as $zona_id => $cantidad) {
                $zona_nombre = $data['zonas_nombres'][$zona_id] ?? "Zona $zona_id";
                $porcentaje = round(($cantidad / $total) * 100, 1);
                $this->Cell(120, 6, $this->sanitize($zona_nombre), 1);
                $this->Cell(35, 6, (string)$cantidad, 1, 0, 'C');
                $this->Cell(0, 6, $porcentaje . '%', 1, 1, 'C');
            }
            $this->Ln(6);
        }

        // ===== RENDIMIENTO SEINCRI =====
        if (!empty($stats['rendimiento_seincri'])) {
            $this->SetFont('Arial', 'B', 12);
            $this->SetFillColor(230, 230, 230);
            $this->Cell(0, 9, $this->sanitize('5. RENDIMIENTO POR AGENTE SEINCRI'), 0, 1, 'L', true);
            $this->Ln(2);

            $this->SetFont('Arial', 'B', 9);
            $this->Cell(70, 7, $this->sanitize('Agente'), 1, 0, 'C', true);
            $this->Cell(25, 7, $this->sanitize('Total'), 1, 0, 'C', true);
            $this->Cell(25, 7, $this->sanitize('Resueltos'), 1, 0, 'C', true);
            $this->Cell(30, 7, $this->sanitize('Investigando'), 1, 0, 'C', true);
            $this->Cell(0, 7, $this->sanitize('Efectividad'), 1, 1, 'C', true);

            $this->SetFont('Arial', '', 9);
            foreach ($stats['rendimiento_seincri'] as $agente_id => $datos_agente) {
                $nombre = $data['usuarios_nombres'][$agente_id] ?? $agente_id;
                $efectividad = $datos_agente['total'] > 0 ? round(($datos_agente['resueltos'] / $datos_agente['total']) * 100, 1) : 0;
                
                $this->Cell(70, 6, $this->sanitize(substr($nombre, 0, 28)), 1);
                $this->Cell(25, 6, (string)$datos_agente['total'], 1, 0, 'C');
                $this->Cell(25, 6, (string)$datos_agente['resueltos'], 1, 0, 'C');
                $this->Cell(30, 6, (string)$datos_agente['investigando'], 1, 0, 'C');
                $this->Cell(0, 6, $efectividad . '%', 1, 1, 'C');
            }
            $this->Ln(6);
        }

        // ===== RENDIMIENTO MESA =====
        if (!empty($stats['rendimiento_mesa'])) {
            $this->SetFont('Arial', 'B', 12);
            $this->SetFillColor(230, 230, 230);
            $this->Cell(0, 9, $this->sanitize('6. PRODUCTIVIDAD MESA DE PARTES'), 0, 1, 'L', true);
            $this->Ln(2);

            $this->SetFont('Arial', 'B', 10);
            $this->Cell(120, 7, $this->sanitize('Usuario Mesa'), 1, 0, 'C', true);
            $this->Cell(35, 7, $this->sanitize('Registros'), 1, 0, 'C', true);
            $this->Cell(0, 7, $this->sanitize('Porcentaje'), 1, 1, 'C', true);

            $this->SetFont('Arial', '', 10);
            arsort($stats['rendimiento_mesa']);
            foreach ($stats['rendimiento_mesa'] as $mesa_id => $cantidad) {
                $nombre = $data['usuarios_nombres'][$mesa_id] ?? $mesa_id;
                $porcentaje = round(($cantidad / $total) * 100, 1);
                $this->Cell(120, 6, $this->sanitize(substr($nombre, 0, 40)), 1);
                $this->Cell(35, 6, (string)$cantidad, 1, 0, 'C');
                $this->Cell(0, 6, $porcentaje . '%', 1, 1, 'C');
            }
        }

        // Crear directorio si no existe
        $pdfDir = dirname($ruta);
        if (!is_dir($pdfDir)) {
            mkdir($pdfDir, 0755, true);
        }

        $this->Output('F', $ruta);
        return $ruta;
    }
}
?>

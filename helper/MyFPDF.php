<?php

require_once __DIR__ . '/../vendor/fpdf/fpdf.php';

class MyFPDF extends FPDF
{
    /**
     * Genera y envía el PDF al navegador
     */
    public function exportar(array $data)
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 16);

        $titulo = "Estadísticas por " . $data["opcion"];
        $tabla = $data["tabla"];
        $grafico = $data["grafico"];

        // ---- TITULO ----
        $this->Cell(0, 10, utf8_decode($titulo), 0, 1, 'C');
        $this->Ln(5);

        // ---- IMAGEN DEL GRÁFICO (base64) ----
        if (strpos($grafico, 'base64') !== false) {

            // Cortamos "data:image/png;base64,"
            $grafico = preg_replace('/^data:image\/\w+;base64,/', '', $grafico);

            $tmpFile = sys_get_temp_dir() . '/grafico_' . uniqid() . '.png';
            file_put_contents($tmpFile, base64_decode($grafico));

            // Dibujar imagen (ancho 180mm)
            $this->Image($tmpFile, 15, $this->GetY(), 180);

            // Avanzar cursor por debajo del gráfico
            $this->SetY($this->GetY() + 100);

            unlink($tmpFile);
        }

        // ---- TABLA ----
        $this->crearTabla($tabla);

        // ---- MOSTRAR PDF ----
        $this->Output("I", "estadisticas.pdf");
    }


    /**
     * Genera una tabla básica a partir de la respuesta SQL
     */
    private function crearTabla(array $tabla)
    {
        $this->SetFont('Arial', '', 10);

        $columnas = array_keys($tabla[0]);
        $numColumnas = count($columnas);
        $anchoColumna = 180 / $numColumnas;

        // Si no entra la tabla en la página, agregar otra
        if ($this->GetY() > 250) {   // umbral aproximado
            $this->AddPage();
        }

        // ---- Encabezados ----
        foreach ($columnas as $col) {
            $this->Cell($anchoColumna, 8, utf8_decode($col), 1, 0, 'C');
        }
        $this->Ln();

        // ---- Filas ----
        foreach ($tabla as $fila) {

            // Salto de página automático si no hay espacio
            if ($this->GetY() > 270) {
                $this->AddPage();

                // Reimprimir encabezado
                foreach ($columnas as $col) {
                    $this->Cell($anchoColumna, 8, utf8_decode($col), 1, 0, 'C');
                }
                $this->Ln();
            }

            foreach ($fila as $valor) {
                $this->Cell($anchoColumna, 8, utf8_decode($valor), 1, 0, 'C');
            }
            $this->Ln();
        }
    }
}

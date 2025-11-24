<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class MyDomPdf
{
    private $dompdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isRemoteEnabled', true); // permite imágenes base64

        $this->dompdf = new Dompdf($options);
    }

    public function exportar(array $data)
    {
        $html = $this->armarHtml($data);

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper("A4", "portrait");
        $this->dompdf->render();

        $this->dompdf->stream("estadisticas.pdf", ["Attachment" => false]);
    }

    private function armarHtml(array $data): string
    {
        $titulo = "Estadísticas por " . $data["opcion"];
        $tabla = $data["tabla"];
        $grafico = $data["grafico"];

        ob_start();
        ?>

        <html>
        <head>
            <style>
                body { font-family: Helvetica, Arial; margin: 20px; }
                h1 { text-align: center; }
                table { width: 100%; border-collapse: collapse; margin-top: 25px; }
                th, td { border: 1px solid #444; padding: 6px; text-align: center; }
                th { background: #eee; }
                img { display: block; margin: 20px auto; max-width: 100%; }
            </style>
        </head>
        <body>

        <h1><?= $titulo ?></h1>

        <img src="<?= $grafico ?>" alt="Gráfico" />

        <table>
            <thead>
            <tr>
                <?php foreach (array_keys($tabla[0]) as $columna): ?>
                    <th><?= htmlspecialchars($columna) ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tabla as $fila): ?>
                <tr>
                    <?php foreach ($fila as $valor): ?>
                        <td><?= htmlspecialchars($valor) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        </body>
        </html>

        <?php
        return ob_get_clean();
    }
}

<?php

require_once __DIR__ . '/../vendor/jpgraph/src/jpgraph.php';
require_once __DIR__ . '/../vendor/jpgraph/src/jpgraph_bar.php';
require_once __DIR__ . '/../helper/MyDomPdf.php';

class EstadisticasModel
{
    private $conexion;
    private $mapaVariablesEnBaseDeDatos;
    private $mapaCasesSegunVariable;
    private $opcionesDeTablaUsuario;
    private $domPdf;

    public function __construct($conexion){
        $this->conexion = $conexion;

        $this->mapaVariablesEnBaseDeDatos = [
            'País' => 'pais',
            'Sexo' => 'idSexo',
            'Edad' => 'anioNacimiento',
            'Partidas' => 'idPartida',
            'Resultados' => 'idResultado',
            'PreguntasSugeridas' => 'idPregunta',
        ];

        $this->mapaCasesSegunVariable = [
            'País' => "WHEN f.pais IN (SELECT pais FROM top_resultados) THEN f.pais ELSE 'Otros'",

            'Sexo' => "WHEN f.idSexo IN (SELECT idSexo FROM top_resultados) THEN (SELECT s.descripcion FROM sexo s WHERE s.idSexo = f.idSexo)",

            'Edad' => "WHEN TIMESTAMPDIFF( YEAR, STR_TO_DATE(CONCAT(f.anioNacimiento, '-01-01'), '%Y-%m-%d'),
                     CURDATE() ) < 18 THEN 'Menores'
                      WHEN TIMESTAMPDIFF( YEAR, STR_TO_DATE(CONCAT(f.anioNacimiento, '-01-01'), '%Y-%m-%d'), CURDATE()
             ) >= 65 THEN 'Jubilados' ELSE 'Adultos'"
        ];

        $this->opcionesDeTablaUsuario = ['País', 'Sexo', 'Edad'];

        $this->domPdf = new MyDomPdf();
    }

    public function getEstadistica($opcion, $periodo){
        $tabla = [];
        if (in_array($opcion, $this->opcionesDeTablaUsuario)) {
            $tabla = $this->getFromUsuario($opcion, $periodo);
        } else {
            $funcion = "getBy" . $opcion;
            $tabla = $this->$funcion($opcion, $periodo);
        }
        if (!$tabla) {
            return ['usuarioLogueado' => $_SESSION['usuarioLogueado'],
                    'mensaje' => 'No se encontraron resultados',];
        }
        $grafico = $this->generarGrafico($tabla, $opcion);
        return ['usuarioLogueado' => $_SESSION['usuarioLogueado'],
            'tabla' => $tabla,
            'grafico' => $grafico,
            'opcion' => $opcion,
            'periodo' => $periodo];
    }

    public function exportarPdf($opcion, $periodo){
        $data = $this->getEstadistica($opcion, $periodo);
        $this->domPdf->exportar($data);
    }

    public function getFromUsuario($opcion, $periodo){
        $periodo = $this->getQueryPeriodo($periodo, 'usuario');
        $opcionSql = $this->mapaVariablesEnBaseDeDatos[$opcion];
        $contenidoCase = $this->mapaCasesSegunVariable[$opcion];
        $sql = "WITH top_resultados AS (
    SELECT $opcionSql FROM usuario WHERE $opcionSql IS NOT NULL AND $opcionSql <> ''
    GROUP BY $opcionSql ORDER BY COUNT(*) DESC LIMIT 5),
     filtered AS (
         SELECT
             nt.$opcionSql,
             nt.fechaRegistro,
                     $periodo
     ) SELECT
    f.periodo,
    CASE
        $contenidoCase
        END AS $opcion,
    COUNT(*) AS cantidad
FROM filtered f
GROUP BY periodo, $opcion
ORDER BY periodo ASC, cantidad DESC;";
        return $this->conexion->query($sql);
    }

    public function getByPartidas($opcion, $periodo){
        $periodo = $this->getQueryPeriodo($periodo, 'partida');
        $opcionSql = $this->mapaVariablesEnBaseDeDatos[$opcion];
        $sql = "WITH filtered AS ( SELECT
             DISTINCT nt.$opcionSql,
                      $periodo
     ) SELECT f.periodo, CASE
        WHEN f.$opcionSql IN (SELECT DISTINCT f.$opcionSql FROM filtered) THEN 'Subtotales'
        END AS $opcion,
    COUNT(*) AS cantidad
FROM filtered f
GROUP BY periodo, $opcion
ORDER BY periodo ASC, cantidad DESC;";
        return $this->conexion->query($sql);
    }

    public function getByResultados($opcion, $periodo){
        $periodo = $this->getQueryPeriodo($periodo, 'partida', 'partida_tiene_pregunta');
        $opcionSql = $this->mapaVariablesEnBaseDeDatos[$opcion];
        $sql = "WITH filtered AS ( SELECT
        ta.$opcionSql,
        $periodo
) SELECT f.periodo, CASE
        WHEN f.$opcionSql IN (SELECT f.$opcionSql FROM filtered) THEN (select r.descripcion from resultado r where r.$opcionSql = f.$opcionSql)
        ELSE 'Otros'
        END AS $opcion,
    COUNT(*) AS cantidad
FROM filtered f
GROUP BY periodo, $opcion
ORDER BY periodo ASC, cantidad DESC;";
        return $this->conexion->query($sql);
    }

    public function getByPreguntasSugeridas($opcion, $periodo){
        $periodo = $this->getQueryPeriodo($periodo, 'pregunta');
        $opcionSql = $this->mapaVariablesEnBaseDeDatos[$opcion];
        $sql = "WITH filtered AS ( SELECT
             DISTINCT nt.$opcionSql,
                      $periodo
         and nt.idUsuarioCreador is not null
     ) SELECT f.periodo, CASE
        WHEN f.$opcionSql IN (SELECT DISTINCT f.$opcionSql FROM filtered) THEN 'Subtotales'
        END AS $opcion,
    COUNT(*) AS cantidad
FROM filtered f
GROUP BY periodo, $opcion
ORDER BY periodo ASC, cantidad DESC;";
        return $this->conexion->query($sql);
    }

    public function getQueryPeriodo($periodo , $nombreTabla, $tablaAdicional = null): string{
        $queryPeriodo = "";
        $join = "";
        if ($tablaAdicional != null) {
            $join = "join $tablaAdicional as ta on nt.idPartida = ta.idPartida";
        }
        switch ($periodo) {
            case 'dia':
                $queryPeriodo = "DATE_FORMAT(nt.fechaRegistro, '%Y-%m-%d %H:00') AS periodo FROM " . $nombreTabla . " nt " . $join .
             " WHERE (nt.fechaRegistro >= DATE_SUB(NOW(), INTERVAL 24 HOUR))";
                break;
            case 'semana':
                $queryPeriodo = "DATE(nt.fechaRegistro) AS periodo FROM " . $nombreTabla . " nt " . $join . " WHERE
             (nt.fechaRegistro >= DATE_SUB(NOW(), INTERVAL 7 DAY))";
                break;
            case 'mes':
                $queryPeriodo = "CONCAT( 'Semana ', CEIL( TIMESTAMPDIFF(DAY, DATE_SUB(NOW(), INTERVAL 28 DAY), nt.fechaRegistro) / 7 ) )
                 AS periodo FROM " . $nombreTabla . " nt " . $join . " WHERE (nt.fechaRegistro >= DATE_SUB(NOW(), INTERVAL 28 DAY))";
                break;
            case 'anio':
                $queryPeriodo = "DATE_FORMAT(nt.fechaRegistro, '%Y-%m') AS periodo FROM " . $nombreTabla . " nt " . $join .
            " WHERE (nt.fechaRegistro >= DATE_SUB(NOW(), INTERVAL 12 MONTH))";
                break;
        } return $queryPeriodo;
    }

    /*public function generarGrafico($tabla, $opcion){
        $datos = $this->procesarTabla($tabla, $opcion);
        $titulo = "Estadisticas por " . $opcion;
        $periodo = $datos['periodo'];   // eje X
        $varibleSeleccionada = $datos['variable'];   // leyenda
        $valores = $datos['valores']; // valores por país

        // Crear gráfico
        $grafico = new Graph(900, 500);
        $grafico->SetScale("textlin");

        // Márgenes del gráfico
        $grafico->img->SetMargin(60, 40, 40, 80);

        // Título
        $grafico->title->Set($titulo);
        $grafico->title->SetFont(FF_FONT1, FS_BOLD);

        // Etiquetas del eje X
        $grafico->xaxis->SetTickLabels($periodo);
        $grafico->xaxis->SetLabelAngle(50);
        $grafico->xaxis->title->Set('Periodo de tiempo');

        // Eje Y
        $grafico->yaxis->title->Set('Cantidad');

        // Colores personalizados para cada país
        $colores = [
            "#005DF0",  // azul
            "#00EF48",  // verde
            "#26F0EC",  // celeste
            "#F09D26",  // naranja
            "#EF264D",  // rojo
            "#8A26F0"   // violeta
        ];

        // Crear barras
        $barras = [];
        $i = 0;
        foreach ($varibleSeleccionada as $vs) {
            $serie = new BarPlot($valores[$vs]);
            $serie->SetLegend($vs);
            $serie->SetFillColor($colores[$i % count($colores)]);
            $barras[] = $serie;
            $i++;
        }

        // Agrupar barras
        $grupo = new GroupBarPlot($barras);
        $grafico->Add($grupo);

        // Estilo de la leyenda
        $grafico->legend->SetFrameWeight(1);
        $grafico->legend->SetColumns(3);

        // Salida del gráfico como imagen
        $filename = '{{BASE_URL}}imagenes/graficos/' . $_SESSION['usuarioLogueado']['idUsuario'] . '.png';
        if (file_exists($filename)) {
            unlink($filename);}
        $grafico->Stroke($filename);

        return $filename;
    }
*/
    public function generarGrafico($tabla, $opcion){
        $datos = $this->procesarTabla($tabla, $opcion);
        $titulo = "Estadisticas por " . $opcion;
        $periodo = $datos['periodo'];   // eje X
        $varibleSeleccionada = $datos['variable'];   // leyenda
        $valores = $datos['valores']; // valores por país

        // Crear gráfico
        $grafico = new Graph(900, 500);
        $grafico->SetScale("textlin");

        // Márgenes del gráfico
        $grafico->img->SetMargin(60, 40, 40, 80);

        // Título
        $grafico->title->Set($titulo);
        $grafico->title->SetFont(FF_FONT1, FS_BOLD);

        // Etiquetas del eje X
        $grafico->xaxis->SetTickLabels($periodo);
        $grafico->xaxis->SetLabelAngle(50);
        $grafico->xaxis->title->Set('Periodo de tiempo');

        // Eje Y
        $grafico->yaxis->title->Set('Cantidad');

        // Colores personalizados para cada país
        $colores = [
            "#005DF0", "#00EF48", "#26F0EC",
            "#F09D26", "#EF264D", "#8A26F0"
        ];

        // Crear barras
        $barras = [];
        $i = 0;
        foreach ($varibleSeleccionada as $vs) {
            $serie = new BarPlot($valores[$vs]);
            $serie->SetLegend($vs);
            $serie->SetFillColor($colores[$i % count($colores)]);
            $barras[] = $serie;
            $i++;
        }

        // Agrupar barras
        $grupo = new GroupBarPlot($barras);
        $grafico->Add($grupo);

        // Estilo de la leyenda
        $grafico->legend->SetFrameWeight(1);
        $grafico->legend->SetColumns(3);

        /** 🌟 NUEVO: guardar en directorio temporal permitido */
        $tmp = sys_get_temp_dir();
        $filename = $tmp . '/grafico_' . uniqid() . '.png';

        // Generar archivo temporal
        $grafico->Stroke($filename);

        /** 🌟 NUEVO: convertir a base64 */
        $imageData = file_get_contents($filename);
        $imageBase64 = 'data:image/png;base64,' . base64_encode($imageData);

        // Borrar archivo temporal
        unlink($filename);

        /** 🌟 DEVOLVER base64 en vez de ruta */
        return $imageBase64;
    }

    public function procesarTabla($tabla, $opcionIngresada): array{
        $periodo = [];
        $variable = [];
        $valores = [];
        // recolectar nombres de las variables sin repetir
        foreach ($tabla as $row) {
            if (!in_array($row['periodo'], $periodo)) {
                $periodo[] = $row['periodo'];
            }
            if (!in_array($row[$opcionIngresada], $variable)) {
                $variable[] = $row[$opcionIngresada];
            }
        }
        // inicializar todas las series con ceros
        foreach ($variable as $p) {
            $valores[$p] = array_fill(0, count($periodo), 0);
        }
        // llenar valores según correspondan
        foreach ($tabla as $row) {
            $idxPeriodo = array_search($row['periodo'], $periodo);
            $opcion = $row[$opcionIngresada];
            $valores[$opcion][$idxPeriodo] += (int)$row['cantidad'];
        }
        return [
            'periodo' => $periodo,
            'variable' => $variable,
            'valores' => $valores
        ];
    }



}
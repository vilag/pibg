<?php
require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Extrae del PDF del calendario anual (formato: una pagina por mes, tabla
 * FECHA/ACTIVIDAD/HORARIO/ENCARGADOS) una lista de filas crudas por mes.
 * No asume texto en columnas alineadas por espacios (eso falla entre PDFs
 * distintos) — usa la posicion real (x,y) de cada fragmento de texto para
 * reconstruir las filas y columnas, comparando contra la posicion del
 * encabezado de esa misma pagina.
 */
function calendario_extraer_filas_pdf($rutaArchivo)
{
    $meses = [
        'ENERO' => 1, 'FEBRERO' => 2, 'MARZO' => 3, 'ABRIL' => 4, 'MAYO' => 5, 'JUNIO' => 6,
        'JULIO' => 7, 'AGOSTO' => 8, 'SEPTIEMBRE' => 9, 'OCTUBRE' => 10, 'NOVIEMBRE' => 11, 'DICIEMBRE' => 12,
    ];

    $parser = new \Smalot\PdfParser\Parser();
    $pdf = $parser->parseFile($rutaArchivo);

    $anio = null;
    $filas = [];

    foreach ($pdf->getPages() as $page) {
        $rawData = $page->getDataTm();
        $frags = [];
        foreach ($rawData as $d) {
            $frags[] = ['x' => $d[0][4], 'y' => $d[0][5], 't' => calendario_pdf_normaliza($d[1])];
        }

        if ($anio === null) {
            $todoElTexto = implode('', array_column($frags, 't'));
            if (preg_match('/\b(20\d{2})\b/', $todoElTexto, $m)) {
                $anio = (int) $m[1];
            }
        }

        $mesNum = null;
        foreach ($frags as $f) {
            if (isset($meses[$f['t']])) { $mesNum = $meses[$f['t']]; break; }
        }
        if ($mesNum === null) continue;

        $hx = [];
        $headerY = null;
        foreach ($frags as $f) {
            if ($f['t'] === 'FECHA') { $hx['fecha'] = $f['x']; $headerY = $f['y']; }
            if ($f['t'] === 'ACTIVIDAD') $hx['actividad'] = $f['x'];
            if ($f['t'] === 'HORARIO') $hx['horario'] = $f['x'];
            if ($f['t'] === 'ENCARGADOS') $hx['encargados'] = $f['x'];
        }
        if (count($hx) < 4 || $headerY === null) continue;

        $b1 = ($hx['fecha'] + $hx['actividad']) / 2;
        $b2 = ($hx['actividad'] + $hx['horario']) / 2;
        $b3 = ($hx['horario'] + $hx['encargados']) / 2;

        $datosFrags = array_values(array_filter($frags, fn($f) => $f['y'] < $headerY - 0.5));
        usort($datosFrags, fn($a, $b) => $b['y'] <=> $a['y']);

        $filasPagina = [];
        $actual = [];
        $ultimaY = null;
        foreach ($datosFrags as $f) {
            if ($ultimaY !== null && ($ultimaY - $f['y']) > 5.0) {
                $filasPagina[] = $actual;
                $actual = [];
            }
            $actual[] = $f;
            $ultimaY = $f['y'];
        }
        if ($actual) $filasPagina[] = $actual;

        foreach ($filasPagina as $celdas) {
            usort($celdas, fn($a, $b) => $a['x'] <=> $b['x']);
            $cols = ['', '', '', ''];
            foreach ($celdas as $c) {
                if ($c['x'] < $b1) $idx = 0;
                elseif ($c['x'] < $b2) $idx = 1;
                elseif ($c['x'] < $b3) $idx = 2;
                else $idx = 3;
                $cols[$idx] = trim($cols[$idx] . ' ' . $c['t']);
            }
            if ($cols[0] === '' && $cols[1] === '') continue;

            $filas[] = [
                'mes' => $mesNum,
                'fecha_texto' => $cols[0],
                'actividad' => $cols[1],
                'horario_texto' => $cols[2],
                'encargados' => $cols[3],
            ];
        }
    }

    return ['anio' => $anio ?: (int) date('Y'), 'filas' => $filas];
}

function calendario_pdf_normaliza($s)
{
    return trim(preg_replace('/\s+/u', ' ', str_replace("\xc2\xa0", ' ', $s)));
}

/**
 * Convierte una fila cruda (mes, fecha_texto, actividad, horario_texto,
 * encargados) en uno o mas eventos listos para insertar en `calendario`
 * (fecha_hora, dia_nom, nom_activ, tema, tipo). Si fecha_texto describe un
 * rango de dias ("Lun 20 a Vier 24"), genera un evento por cada dia del
 * rango. El nombre del dia se calcula con la fecha real, no con el texto
 * del PDF (evita depender de abreviaturas inconsistentes).
 */
function calendario_expandir_fila($anio, $filaCruda)
{
    $diasEs = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];

    preg_match_all('/\d{1,2}/', $filaCruda['fecha_texto'], $mNums);
    $numeros = $mNums[0] ?? [];
    if (empty($numeros)) return [];

    $diaInicio = (int) $numeros[0];
    $diaFin = isset($numeros[1]) ? (int) $numeros[1] : $diaInicio;
    if ($diaFin < $diaInicio) $diaFin = $diaInicio;

    $hora = '00:00:00';
    if (preg_match('/(\d{1,2})(?:[:.](\d{2}))?/', $filaCruda['horario_texto'], $mHora)) {
        $h = min(23, (int) $mHora[1]);
        $min = isset($mHora[2]) ? (int) $mHora[2] : 0;
        $hora = sprintf('%02d:%02d:00', $h, $min);
    }

    $nombre = trim(preg_replace('/\s+/u', ' ', $filaCruda['actividad']));
    $tema = trim(preg_replace('/\s+/u', ' ', $filaCruda['encargados']));

    $mes = $filaCruda['mes'];
    $eventos = [];
    for ($d = $diaInicio; $d <= $diaFin; $d++) {
        if (!checkdate($mes, $d, $anio)) continue;
        $ts = mktime(0, 0, 0, $mes, $d, $anio);
        $eventos[] = [
            'fecha' => sprintf('%04d-%02d-%02d', $anio, $mes, $d),
            'hora' => $hora,
            'dia_nom' => $diasEs[(int) date('w', $ts)],
            'nom_activ' => $nombre,
            'tema' => $tema,
            'tipo' => 0,
        ];
    }
    return $eventos;
}

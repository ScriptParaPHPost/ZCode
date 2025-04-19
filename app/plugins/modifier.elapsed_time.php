<?php

/**
 * Smarty plugin - elapsed_time en español
 * @param int $timestamp Timestamp a evaluar
 * @param string $formato Opcional. Letras: y = años, m = meses, d = días, h = horas, i = minutos, s = segundos
 * @return string
 */
function smarty_modifier_elapsed_time($tiempo, $formato = 'dhis') {
    if (!is_numeric($tiempo)) {
        $tiempo = strtotime($tiempo);
    }

    $ahora = time();
    $diferencia = $ahora - $tiempo;

    if ($diferencia < 0) return 'en el futuro';

    $unidades = [
        'y' => ['segundos' => 31536000, 'singular' => 'año', 'plural' => 'años'],
        'm' => ['segundos' => 2592000,  'singular' => 'mes', 'plural' => 'meses'],
        'd' => ['segundos' => 86400,    'singular' => 'día', 'plural' => 'días'],
        'h' => ['segundos' => 3600,     'singular' => 'hora', 'plural' => 'horas'],
        'i' => ['segundos' => 60,       'singular' => 'minuto', 'plural' => 'minutos'],
        's' => ['segundos' => 1,        'singular' => 'segundo', 'plural' => 'segundos'],
    ];

    $resultado = [];
    foreach (str_split($formato) as $unidad) {
        if (!isset($unidades[$unidad])) continue;

        $segundosUnidad = $unidades[$unidad]['segundos'];
        $cantidad = floor($diferencia / $segundosUnidad);

        if ($cantidad > 0 || empty($resultado)) {
            $nombre = $cantidad === 1 ? $unidades[$unidad]['singular'] : $unidades[$unidad]['plural'];
            $resultado[] = "$cantidad $nombre";
        }

        $diferencia %= $segundosUnidad;
    }

    return implode(' ', $resultado);
}

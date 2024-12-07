<?php
/**
 * Smarty fecha modifier plugin
 *
 * Type:     modifier
 * Name:     fecha
 * Date:     Jun 27, 2024
 * Purpose:  Formatea una fecha en varios formatos posibles
 * Input:    timestamp, string
 * Example:  {$var|fecha}
 * Author:   Miguel92
 * Version:  2.0
 * @param int $fecha
 * @param string $format
 * @return string
*/

function smarty_modifier_fecha($fecha, $format = false) {
   // Predefinir arrays de nombres de días y meses
   static $MESES = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
   static $DIAS = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];

   // Obtener la información básica de fecha
   $dia = date("d", $fecha);
   $mes_int = date("n", $fecha) - 1; // Ajuste para el índice de mes
   $ano = date("Y", $fecha);
   $hora = date("H", $fecha);
   $minuto = date("i", $fecha);
   $segundos = date("s", $fecha);
   $week = date("N", $fecha); // Índice de día de la semana (1 = lunes, 7 = domingo)
   $e_ano = date("Y"); // Año actual

   return match ($format) {
      'd_Ms_a' => "$dia de {$MESES[$mes_int]}" . ($e_ano === $ano ? '' : " de $ano"),
      'd-m-Y' => date("d-m-Y", $fecha),
      'd/m/Y' => date("d/m/Y", $fecha),
      'Y-m-d' => date("Y-m-d", $fecha),
      'date' => "$dia {$MESES[$mes_int]} $ano",
      'date-hours' => "{$DIAS[$week - 1]}, $dia {$MESES[$mes_int]} $ano $hora:$minuto:$segundos",
      default => date('d.m.y', $fecha),
   };
}
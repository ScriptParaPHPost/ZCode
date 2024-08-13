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
   $_meses = array('', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
   $_dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
   
   // FORMATO
   if ($format !== false) {
      $dia = date("d", $fecha);
      $mes = date("m", $fecha);
      $mes_int = date("n", $fecha);
      $ano = date("Y", $fecha);
      $hora = date("H", $fecha);
      $minuto = date("i", $fecha);
      $e_ano = date("Y", time());
      
      switch ($format) {
         case 'd_Ms_a':
            $ano = ($e_ano == $ano) ? '' : ' de ' . $ano;
            return $dia . ' de ' . $_meses[$mes_int] . $ano;
         case 'd-m-Y':
            return date("d-m-Y", $fecha);
         case 'd/m/Y':
            return date("d/m/Y", $fecha);
         case 'Y-m-d':
            return date("Y-m-d", $fecha);
         case 'd M Y':
            return $dia . ' ' . $_meses[$mes_int] . ' ' . $ano;
         case 'D, d M Y H:i:s':
            return $_dias[date("w", $fecha)] . ', ' . $dia . ' ' . $_meses[$mes_int] . ' ' . $ano . ' ' . $hora . ':' . $minuto . ':' . date("s", $fecha);
         default:
            return date($format, $fecha); // Permite formatos personalizados usando date()
      }
   } else {
      // Formato "hace X tiempo"
      $ahora = time();
      $tiempo = $ahora - $fecha;
      $dias = round($tiempo / 86400);
      
      if ($dias <= 0) {
         if (round($tiempo / 3600) <= 0) {
            if (round($tiempo / 60) <= 0) {
               return $tiempo <= 60 ? "Hace unos segundos" : '';
            } else {
               $can = round($tiempo / 60);
               $word = $can <= 1 ? "minuto" : "minutos";
               return 'Hace ' . $can . ' ' . $word;
            }
         } else {
            $can = round($tiempo / 3600);
            $word = $can <= 1 ? "hora" : "horas";
            return 'Hace ' . $can . ' ' . $word;
         }
      } else if ($dias <= 7) {
         if ($dias < 2) {
            return 'Ayer a las ' . date("H:i", $fecha);
         } else {
            return 'El ' . $_dias[date("w", $fecha)] . ' a las ' . date("H:i", $fecha);
         }
      } else {
         return "El " . date("d", $fecha) . " de " . $_meses[date("n", $fecha)] . " a las " . date("H:i", $fecha);
      }
   }
}
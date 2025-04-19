<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty cat modifier plugin
 *
 * Type:     modifier<br>
 * Name:     hace<br>
 * Date:     Feb 24, 2010
 * Purpose:  catenate a value to a variable
 * Input:    string to catenate
 * Example:  {$var|cat:"foo"}
 * @author   Ivan Molina Pavana
 * @version 1.0
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_hace($fecha, bool $show = false) {
   if (!$fecha) return "Nunca";

   $tiempo = time() - $fecha; // Tiempo transcurrido desde la fecha proporcionada
   if ($tiempo < 0) return "Nunca";

   // Definimos unidades de tiempo en segundos con su formato singular/plural
   $unidades = [
      31536000 => ["a&ntilde;o", "a&ntilde;os"], // Un año: 365 días
      2678400 => ["mes", "meses"],           // Un mes: ~30 días
      604800 => ["semana", "semanas"],      // Una semana: 7 días
      86400 => ["d&iacute;a", "d&iacute;as"],  // Un día: 24 horas
      3600 => ["hora", "horas"],           // Una hora: 60 minutos
      60 => ["minuto", "minutos"]         // Un minuto: 60 segundos
   ];
   if ($tiempo <= 60) return $show ? "Hace instantes" : "instantes";
   foreach ($unidades as $segundos => $nombre) {
      $round = round($tiempo / $segundos);
      if ($round >= 1) {
         $hace = "{$round} " . ($round > 1 ? $nombre[1] : $nombre[0]);
         break;
      }
   }
   return $show ? "Hace $hace" : $hace;
}
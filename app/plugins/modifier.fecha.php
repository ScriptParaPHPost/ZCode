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
   $_meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
   $_dias = ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'];
   
   // FORMATO
      $dia = date("d", $fecha);
      $mes = date("m", $fecha);
      $mes_int = date("n", $fecha) - 1;
      $ano = date("Y", $fecha);
      $hora = date("H", $fecha);
      $minuto = date("i", $fecha);
      $segundos = date("s", $fecha);
      $week = date("N", $fecha);
      $e_ano = date("Y", time());
      
      switch ($format) {
         case 'd_Ms_a':
            $ano_match = "$dia de {$_meses[$mes_int]}" . ($e_ano === $ano ? '' : " de $ano");
         break;
         case 'd-m-Y':
            $ano_match = date("d-m-Y", $fecha);
         break;
         case 'd/m/Y':
            $ano_match = date("d/m/Y", $fecha);
         break;
         case 'Y-m-d':
            $ano_match = date("Y-m-d", $fecha);
         break;
         case 'date':
            $ano_match = "$dia {$_meses[$mes_int]} $ano";
         break;
         case 'date-hours':
            $ano_match = "{$_dias[$week]}, $dia {$_meses[$mes_int]} $ano $hora:$minuto:$segundos";
         break;
         
         default:
            $ano_match = date('d.m.y', $fecha);
         break;
      }
      
      return $ano_match;

}
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
function smarty_modifier_hace(int $fecha = null, $show = false){
		# Creamos
		$tiempo = time() - $fecha;
		if($fecha <= 0) return "Nunca";
		// Declaración de unidades de tiempo, aunque es un aproximado
		// Ya que existe años bisiestos 366 días
		$unidades = [
		  31536000 => ["a&ntilde;o", "a&ntilde;os"],
		  2678400 => ["mes", "meses"],
		  604800 => ["semana", "semanas"],
		  86400 => ["d&iacute;a", "d&iacute;as"],
		  3600 => ["hora", "horas"],
		  60 => ["minuto", "minutos"],
		];
		foreach($unidades as $segundos => $nombre){
			$round = round($tiempo / $segundos);
			$s = ($segundos === 2678400) ? 'es' : 's';
			if($tiempo <= 60) $hace = "instantes";
			else {
				if($round > 0) {
					$hace = "{$round} {$nombre[($round > 1 ? 1 : 0)]}";
					break;
				}
			}
		}
		// Si se ha establecido la opción $show, se agrega 'Hace' al resultado
		return ($show ? "Hace " : "") . $hace;
	}

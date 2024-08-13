<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


function smarty_modifier_seo($string){
	// Convertir la cadena a UTF-8 y entidades HTML
	$string = mb_convert_encoding($string, 'UTF-8', 'auto');
	$string = htmlentities($string, ENT_QUOTES, 'UTF-8');
	// Reemplazar entidades HTML comunes en español por sus equivalentes
	$string = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $string);
	// Decodificar entidades HTML
	$string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
	// Reemplazar cualquier carácter no alfanumérico por guiones
	$string = preg_replace('~[^0-9a-z]+~i', '-', $string);
	// Eliminar guiones al inicio y al final
	$string = trim($string, '-');
	// Convertir a minúsculas si es necesario
	$string = strtolower($string);
	return $string;
}
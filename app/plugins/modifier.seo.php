<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Convierte una cadena de texto en un formato SEO-friendly.
 *
 * @param string $string La cadena de texto a convertir.
 * @return string La cadena de texto convertida para SEO, con caracteres especiales reemplazados por guiones.
 */
function smarty_modifier_seo(string $string, bool $lower = false): string {
   // Convertir la cadena a UTF-8 y entidades HTML
   $string = mb_convert_encoding($string ?? '', 'UTF-8', 'auto');
   $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
   
   // Reemplazar entidades HTML comunes en español por sus equivalentes
   $string = preg_replace('~&([a-zA-Z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $string);
   
   // Decodificar entidades HTML
   $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
   
   // Reemplazar cualquier carácter no alfanumérico por guiones
   $string = preg_replace('~[^0-9a-z]+~i', '-', $string);

   // Convertir a minúsculas si es necesario
	if ($lower) {
		$string = strtolower($string);
	}
   
   // Eliminar guiones al inicio y al final, y convertir a minúsculas
   return trim($string, '-');
}

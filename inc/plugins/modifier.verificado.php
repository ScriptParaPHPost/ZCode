<?php
/**
 * Type:     modifier
 * Name:     verificado
 * Date:     Ago 19, 2024
 * Purpose:  Añade al nombre del usuario el verificado
 * Example:  {$nick|verificado}
 * @author   Miguel92
 * @version 1.0
 * @param int
 * @return string
 * @return string
*/

function smarty_modifier_verificado($nick, $icon = 'verified', $folder = 'others') {
	global $tsUser, $smarty;
	// Obtenemos la verificación desde el usuario
   if($tsUser->getUserIsVerified($nick)) {
		// Nombre del icono SVG
   	$parametros['name'] = $icon;
   	// Carpeta donde se encuentra
   	$parametros['folder'] = $folder;
   	// Cambiamos el tamaño
   	$parametros['size'] = '1.125rem';
   	// Cambiamos el color
   	$parametros['fill'] = '#1E67B9';
   	// 
   	$parametros['style'] = 'position:absolute;margin-top: 3px;margin-left: 3px;';
   	// Buscamos, aplicamos y adjuntamos al nombre
   	$nick .= smarty_function_uicon($parametros, $smarty);
   }
   return $nick;
}
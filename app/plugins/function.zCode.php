<?php

/**
 * Autor: Miguel92
 * Ejemplos: 
 *    - {meta facebook=bool twitter=bool analytics=bool robots=array} 
 *    - {zCode css=array|string js=array|string global=bool aditional=bool notifica=bool}
 * Enlace: #
 * Fecha: Mar 01, 2024 
 * Actualizado: Feb 18, 2025
 * Nombre: zCode
 * Proposito: Añadir las etiquetas necesarias dentro del <head>
 * Tipo: function 
 * Version: 2.0
*/

require TS_PLUGINS . 'zCode' . DIRECTORY_SEPARATOR . 'zCode.class.php';

function smarty_function_zCode($params, &$smarty) {

	# Inicializamos la clase
	$pluginZCode = new SmartyZCode();

	$pluginZCode->version = '2.0';

	# Inicializamos la variable
	$template = "<!-- Plugin ZCode -->\n";

	# Añadimos las hojas de estilos
	if(isset($params["css"])) {
		if(!in_array($GLOBALS['smarty']->tpl_vars['tsPage']->value, ['admin', 'moderacion',  'login', 'registro']) && !isset($params['customizer'])) {
			$template .= $pluginZCode->setStyleCustomized();
		}
		$template .= $pluginZCode->setStylesheets($params["css"]);
	}

	if(isset($params['global'])) {
		$template .= $pluginZCode->setScriptLineGlobal($params['remove'] ?? '');
	}

	# Añadimos las hojas de scripts
	if(isset($params["js"])) {
		$template .= $pluginZCode->setScripts($params["js"]);
	}

	if(isset($params['notifica']) && $params['notifica'] === true || isset($params["aditional"]) && $params['aditional'] === true) {
		$template .= "\n".$pluginZCode->setScriptInLine();
	}
	$template .= "\n<!-- End Plugin ZCode -->";

	return $template;

}
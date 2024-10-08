<?php

/**
 * Autor: Miguel92
 * Ejemplo: {zCode css=["archivo.css"] js=["archivo.js"] favicon="archivo.ico" global=['key1' => 'val1', 'key2' => 'val2']} 
 * Enlace: #
 * Fecha: Mar 01, 2024 
 * Nombre: zCode
 * Proposito: Añadir las etiquetas necesarias dentro del <head>
 * Tipo: function 
 * Version: 1.5
*/

require TS_PLUGINS . 'zCode' . TS_PATH . 'zCode.class.php';

function smarty_function_zCode($params, &$smarty) {

	# Inicializamos la clase
	$pluginZCode = new SmartyZCode($smarty);

	$pluginZCode->version = '1.8';

	# Inicializamos la variable
	$template = '';

	# Añadimos las hojas de estilos
	if(isset($params["css"])) {
		$template .= "<!-- Plugin ZCode: V{$pluginZCode->version} -->\n";
		$template .= $pluginZCode->setStyleCustomized();
		$template .= $pluginZCode->setStylesheets($params["css"]);
	}

	if(isset($params['scriptGlobal'])) {
		$template .= "<!-- Plugin ZCode: V{$pluginZCode->version} -->\n";
		$template .= $pluginZCode->setScriptLineGlobal();
	}

	# Añadimos las hojas de scripts
	if(isset($params["js"])) {
		$template .= "<!-- Plugin ZCode: V{$pluginZCode->version} -->\n";
		$template .= $pluginZCode->setScripts($params["js"]);
	}

	if(isset($params["more"])) {
		if(isset($smarty->tpl_vars['tsMuro']->value['total'])) {
			$template .= "\n<script>\n\tmuro.stream.total = " . (int)$smarty->tpl_vars['tsMuro']->value['total'] . ";\n</script>";
		}
	}

	return $template;

}
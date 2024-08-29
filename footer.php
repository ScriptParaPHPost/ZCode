<?php 

if (!defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * El footer permite mostrar la plantilla
 *
 * @name    footer.php
 * @author  ZCode | PHPost
 */

/*
 * -------------------------------------------------------------------
 *  Realizamos tareas para mostrar la plantilla
 * -------------------------------------------------------------------
*/

	// P�gina solicitada
	$smarty->assign("tsPage", $tsPage);

	# Por si quieren cambiar la p�gina de error
	# Si no encuentra la plantilla t.$tsPage.tpl
	# Mostrar esta p�gina
	$smarty->template_error = '404.html';
	
	$smarty->loadAllTemplates(TS_TEMA, $tsPage);
	$smarty->loadTemplate($tsPage);
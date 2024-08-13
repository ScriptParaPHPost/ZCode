<?php 

if (!defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * El footer permite mostrar la plantilla
 *
 * @name    footer.php
 * @author  Miguel92 & PHPost.es
 */

/*
 * -------------------------------------------------------------------
 *  Realizamos tareas para mostrar la plantilla
 * -------------------------------------------------------------------
*/

	// Página solicitada
	$smarty->assign("tsPage", $tsPage);

	# Por si quieren cambiar la página de error
	# Si no encuentra la plantilla t.$tsPage.tpl
	# Mostrar esta página
	$smarty->template_error = '404.html';

	$smarty->loadAllTemplates(TS_TEMA, $tsPage);
	$smarty->loadTemplate($tsPage);
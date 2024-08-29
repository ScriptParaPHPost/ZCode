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

	// Página solicitada
	$smarty->assign("tsPage", $tsPage);

	# Por si quieren cambiar la página de error
	# Si no encuentra la plantilla t.$tsPage.tpl
	# Mostrar esta página
	$template_error = '404.html';
	$template = 'main.tpl';

	// Habilita la comprobación de compilación para un rendimiento óptimo
	$smarty->setCompileCheck(TRUE);

	// Establece el directorio de compilación de plantillas
	$smarty->setCompileDir(TS_CACHE . 'admin');

	// Agrega directorio de plugins Smarty
	$smarty->addPluginsDir(TS_PLUGINS);

	// Suprime advertencias de variables indefinidas o nulas
	$smarty->muteUndefinedOrNullWarnings();

	$dirs['root'] = TS_ROOT;
	$dirs['assets'] = TS_ASSETS;
	$dirs['templates'] = TS_ADMIN . 'templates' . TS_PATH;
	$dirs['admin'] = $dirs['templates'] . 'admin' . TS_PATH;
	$dirs['moderacion'] = $dirs['templates'] . 'moderacion' . TS_PATH;

	$smarty->addTemplateDir($dirs);
	if($tsUser->is_member <= 0) header("Location: ../login/");
	try {
		$temp = $smarty->templateExists($template) ? $template : $template_error;
		$smarty->display($temp);
	} catch (Exception $e) {
		// Muestra un mensaje de error si no se puede cargar la plantilla
		$message = $e->getMessage();
		$patron = "/'([^']+)'/";
		$message_2 = preg_replace_callback($patron, function($matches) {
    		return "'<strong>{$matches[1]}</strong>'";
		}, $message);
		$show = <<<COMENTARIO
		Lo sentimos, se produjo un error al cargar la plantilla <strong>$template</strong>.
		<br>Debido al error:<br> <code style="font-size:1rem;line-height: 1.3rem;color: #d971ad;word-wrap: break-word;background: rgba(217, 113, 173, .12);display:block;padding:.5em;">$message_2</code>
COMENTARIO;
		echo $show;
	}
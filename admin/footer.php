<?php 

if ( ! defined('ZCODE2')) exit('No se permite el acceso directo al script');

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

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

// Registramos plugins
$pluginDirs = [
	'function' => TS_PLUGINS . 'function.*.php',
	'modifier' => TS_PLUGINS . 'modifier.*.php'
];
// Iterar sobre las categorías de plugins
foreach ($pluginDirs as $type => $pattern) {
	// Buscar todos los archivos correspondientes en el directorio
	$files = glob($pattern);
	foreach ($files as $file) {
		require_once $file;
		// Extraer el nombre del plugin (sin la extensión .php)
		$pluginName = explode('.', basename($file, '.php'))[1];
		// Registrar el plugin de acuerdo al tipo
		$smarty->registerPlugin($type, $pluginName, "smarty_{$type}_{$pluginName}");
	}
}
require_once TS_APP . 'extensiones' . DIRECTORY_SEPARATOR . 'zCodeExtensiones.php';
$smarty->addExtension(new zCodeExtensiones());

// Suprime advertencias de variables indefinidas o nulas
$smarty->muteUndefinedOrNullWarnings();

$dirs['root'] = TS_ROOT;
$dirs['assets'] = TS_ASSETS;
$dirs['elements'] = TS_ASSETS . 'elements' . DIRECTORY_SEPARATOR;
$dirs['templates'] = TS_ADMIN . 'templates' . DIRECTORY_SEPARATOR;
$dirs['admin'] = $dirs['templates'] . 'admin' . DIRECTORY_SEPARATOR;
$dirs['moderacion'] = $dirs['templates'] . 'moderacion' . DIRECTORY_SEPARATOR;

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
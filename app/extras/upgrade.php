<?php 

/**
 * @name index.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v2.0.0
 * @description Archivo para cargar todos los datos necesarios para instalar
**/

error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
ini_set('memory_limit', '-1');
set_time_limit(0);

$ServerErrors = '';
require_once '../../config/Polyfill.php';
require_once '../../config/AppVarsGlobal.php';

define('NAMEVERSION', SCRIPT_NAME . ' ' . SCRIPT_VERSION);
define('LICENSE',     file_get_contents(TS_ROOT . 'LICENSE'));
define('FILE_ENV',    TS_ROOT . '.env');
define('FILE_LOCK',   TS_ROOT . '.lock');

define('TS_STORAGE', 	 TS_ROOT . 'storage' . TS_PATH);
define('TS_AVATAR', 		 TS_STORAGE . 'avatar' . TS_PATH);
define('TS_CACHE', 		 TS_STORAGE . 'cache' . TS_PATH);
define('TS_PORTADAS',	 TS_STORAGE . 'portadas' . TS_PATH);
define('TS_UPLOADS', 	 TS_STORAGE . 'uploads' . TS_PATH);

/**
 * Obtenemos la url
*/
function get_protocol_ssl(string $HTTP_STRING = '') {
	$ssl = 'http';
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') $ssl .= 's';
	return $ssl . '://' . $HTTP_STRING;
}
function get_url(string $HTTP_APPEND = '') {
	$REQUEST_URI = dirname($_SERVER["REQUEST_URI"]);
	$QUERY_STRING = ($_SERVER['QUERY_STRING'] === 'step=') ? $REQUEST_URI : dirname($REQUEST_URI);
	$HTTP_HOST = $_SERVER['HTTP_HOST'] ?? 'localhost';
	return get_protocol_ssl($HTTP_HOST.$QUERY_STRING.$HTTP_APPEND);
}
// Obtenemos la url
$ZCODE_LINK = get_url();
$ZCODE_INSTALL = get_url("/install");
	
$next_step = true;

$page = 'inicio';
$pages_array = ['inicio', 'datos', 'requisitos', 'instalacion', 'finalizar'];
if(!empty($_GET['step']) || in_array($_GET['step'], $pages_array)) {
	$page = $_GET['step'];
}

switch ($page) {
	case 'inicio':
		$head = 'Iniciando';
	break;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?= $ZCODE_LINK ?>/assets/css/install.css<?= uniqid('?'.time()) ?>">
<link href="<?= $ZCODE_LINK ?>/assets/images/favicon/logo-32.webp<?= uniqid('?') ?>" rel="icon" type="image/webp">
<title><?= NAMEVERSION ?> | Actualizador</title>
<script src="https://cdn.jsdelivr.net/npm/jquery"></script>
</head>
<body>

	<main>
		<header><?= NAMEVERSION ?> | <?= $head ?></header>
		<section>

			<form method="POST">
				<?php if($page === 'inicio'): ?>
					<div class="box">
						<h3>Centro de actualización.</h3>
						<p>Desde aquí, podrás reinstalar el sitio, borrar datos</p>
						<div class="buttons">
							<a href="./reinstalar">Reinstalar</a>
							<a href="./restaurar">Restaurar base</a>
							<a href="./eliminar_datos">Eliminar datos</a>
						</div>
					</div>
				<?php endif; ?>
			</form>

		</section>
		<footer>
			<p>By <strong><?= SCRIPT_AUTHOR ?></strong></p>
			<p>Copyright &copy; <?= date('Y') ?></p>
			<p><a href="https://discord.gg/mx25MxAwRe" target="_blank">Invitación a Discord</a></p>
		</footer>
	</main>

</body>
</html>
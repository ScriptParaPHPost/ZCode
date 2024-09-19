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
define('TS_AVATARES', 	 TS_ROOT . 'assets' . TS_PATH . 'images' . TS_PATH . 'avatares' . TS_PATH);

if(file_exists(FILE_LOCK)) header("Location: ../");

/**
 * Generador de código para verificación
*/
function key_generator(string $type = 'verify', int $lenght = 12) {
	$caracteres = ($type === 'verify') ? 'Aa1Bb$2Cc#3DSd4Ee5@Ff6Z7O8LP90!-U' : ($type === 'session' ? '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ' : '0A1B2C3D4E5F6L7M8Q9');
	$code = '';
	$max = strlen($caracteres) - 1;
	for($i = 0; $i < $lenght; $i++) {
		$code .= $caracteres[mt_rand(0, $max)];
	}
	if($type === 'session') {
		$code = "ZCODE$code";
	}
	return $code;
}
/**
 * Obtenemos la url
*/
function getSSLProtocol() {
	$ssl = 'http';
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') $ssl .= 's';
	return $ssl;
}

function getUrlNavigation(string $HTTP_APPEND = '', bool $withoutslashes = true) {
	$REQUEST_URI = dirname($_SERVER["REQUEST_URI"]);
	$QUERY_STRING = ($_SERVER['QUERY_STRING'] === 'step=') ? $REQUEST_URI : dirname($REQUEST_URI);
	$HTTP_HOST = $_SERVER['HTTP_HOST'] ?? 'localhost';
	return ($withoutslashes ? '://' : '') . $HTTP_HOST.$QUERY_STRING.$HTTP_APPEND;
}
/**
 * Función para editar y crear el archivo .env
*/
function saveEnvData(array $array = []) {
	if(!file_exists(FILE_ENV)) {
		copy(FILE_ENV . '.example', FILE_ENV);
	}
	$data_content = file_get_contents(FILE_ENV);
	foreach($array as $find => $replace) {
		$data_content = str_replace($find, $replace, $data_content);
	}
	file_put_contents(FILE_ENV, $data_content);
}
//
function isLocalhost() {
   return (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1', 'localhost']));
}

// Obtenemos la url
$ZCODE_LINK = getSSLProtocol().getUrlNavigation();
$ZCODE_INSTALL = getSSLProtocol().getUrlNavigation("/install");

$page = 'licencia';
$pages_array = ['licencia', 'requisitos', 'instalacion', 'finalizar'];
if(!empty($_GET['step']) || in_array($_GET['step'], $pages_array)) {
	$page = $_GET['step'];
}

$next_step = true;

$allFolders = [TS_CACHE, TS_AVATAR, TS_UPLOADS, TS_PORTADAS];
foreach ($allFolders as $folder) {
   if (!is_dir($folder)) {
      mkdir($folder, 0777, true);
   }
   chmod($folder, 0777);
}

switch ($page) {
	case 'licencia':
		$head = 'Licencia';
		$_SESSION['LICENSE'] = (isset($_POST['agree']) && $_POST['agree'] === 'on');
		if($_SESSION['LICENSE']) {
			/**
			 * Realizamos acciones
			*/
			$replace = [
				'__status__' => (isLocalhost() ? 'DEVELOPMENT' : 'PRODUCTION'), 
				'__mode__' => 'true', 
				'__session_name__' => key_generator('session'), 
				'__development__' => 'http://localhost/feed', 
				'__production__' => 'https://zcode.newluckies.com/feed', 
				'__install__' => time(),
				'__script__' => 'WkNvZGVVcGdyYWRl',
				'__key__' => key_generator('verify'),
				'__pin__' => key_generator('pin', 20)
			];
			saveEnvData($replace);
			header("Location: ./requisitos");
			die;
		}
	break;
	case 'requisitos':
		if(!isset($_SESSION['LICENSE']) && $_SESSION['LICENSE'] === false) {
			header("Location: ./");
		}
		if(isset($_POST['reload']) && $_POST['reload'] === 'true') {
			header("Location: ./requisitos");
		}
		function getStatus(bool $action = false, string $text = '') {
			$color = $action ? 'success' : 'danger';
			$text = $action ? $text : 'No ' . $text;
			return ['class' => $color, 'text' => $text];
		}
		$version_support = '7.4.33';
		$head = 'Requisitos del sistema';
		$disabled = false;
		
		$php = version_compare(PHP_VERSION, $version_support, '>=');
		$gd = (extension_loaded('gd') && function_exists('gd_info'));
		$cURL = function_exists('curl_init');
		$mysqli = class_exists('mysqli');
		$mbstring = extension_loaded('mbstring');
		$zip = extension_loaded('zip');
		$is_htaccess = file_exists(TS_ROOT . '.htaccess');

		$version_php = $php ? PHP_VERSION : '';
		$version_gd = $gd ? gd_info()['GD Version'] : '';
		$_SESSION['license'] = true;

		$verify = [
			"cache" => str_replace(TS_ROOT, TS_PATH, TS_CACHE),
			"avatar" => str_replace(TS_ROOT, TS_PATH, TS_AVATAR),
			"uploads" => str_replace(TS_ROOT, TS_PATH, TS_UPLOADS),
			"portadas" => str_replace(TS_ROOT, TS_PATH, TS_PORTADAS)
		];
		foreach ($verify as $key => $val) {
			$rutaVal = ".." . TS_PATH . ".." . $val;
			$permisos[$key]['chmod'] = (int)substr(sprintf('%o', fileperms($rutaVal)), -3);
			$permisos[$key]['css'] = 'success';
			$permisos[$key]['text'] = 'Agregado';
			$permisos[$key]['route'] = $val;
			if ($permisos[$key]['chmod'] !== 777) {
				$permisos[$key]['css'] = 'danger';
				$permisos[$key]['text'] = 'No agregado';
				$disabled = true;
			}
		}
		if(!$php || !$gd || !$cURL || !$mysqli || !$mbstring || !$zip || !$is_htaccess) {
			$disabled = true;
			$_SESSION['license'] = false;
		}
		
		if(isset($_POST['continue']) && $_POST['continue'] === 'true' && $_SESSION['license']) {
			header("Location: ./instalacion");
		}
	break;
	case 'instalacion':
		if(!isset($_SESSION['LICENSE']) && $_SESSION['LICENSE'] === false) {
			header("Location: ./");
		}
		$head = 'Datos de instalación';
		if(isset($_POST['install']) && !empty($_POST['install'])) {
			$_SESSION['LICENSE'] = true;
			$prefix = strtolower($_POST['sql_prefix'] ?? SCRIPT_NAME);
			$prefix .= (substr($prefix, -1) !== '_') ? '_' : '';
			$db = [
				"dbhost" => $_POST['sql_host'],
				"dbuser" => $_POST['sql_user'],
				"dbpass" => $_POST['sql_pass'] ?? '',
				"dbname" => $_POST['sql_name'],
				"dbprefix" => $prefix
			];

			if(empty($db['dbhost']) || empty($db['dbuser']) || empty($db['dbname'])) {
	      	$ServerErrors = "Todos los campos son requeridos";
	      	$_SESSION['LICENSE'] = false;
	      }

	      try {
				$mysqli = new mysqli($db['dbhost'], $db['dbuser'], $db['dbpass'], $db['dbname']);
				if ($mysqli->connect_errno) {
				   $ServerErrors = "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
				   $_SESSION['LICENSE'] = false;
				}
				if (!$mysqli->set_charset("utf8mb4")) {
				  	$ServerErrors = "Error cargando el conjunto de caracteres utf8mb4: " . $mysqli->error;
				  	$_SESSION['LICENSE'] = false;
				}
			} catch (Exception $e) {
				$ServerErrors = $e->getMessage() . ' - code ' . $e->getCode();
				$_SESSION['LICENSE'] = false;
			}
			if(empty($ServerErrors)) {
				saveEnvData($db);
			}
			$db['prefix'] = $prefix;
			// BORRAMOS LAS TABLAS EXISTENTES, EVITANDO ERRORES!
	     	if ($results = $mysqli->query("SHOW TABLES")) {
	     	   while ($row = $results->fetch_row()) {
	     	   	$mysqli->query("DROP TABLE IF EXISTS {$row[0]}");
	     	   }
	     	   $results->close();
	     	} else {
	     	   $ServerErrors = "Error en la consulta: " . $mysqli->error;
	     	   $_SESSION['LICENSE'] = false;
	     	}
	     	# INSTALAMOS LA NUEVA BASE DE DATOS
			require_once '../zcode/database.php';
			$error = '';
			foreach ($zcode_sql as $key => $sentencia) {
				if ($mysqli->query($sentencia)) $exe[$key] = 1;
				else {
					$exe[$key] = 0;
					$error .= '<br/>' . $mysqli->error;
				}
			}
			if (in_array(1, $exe)) {
				$site = [
					"titulo" => $_POST['site_title'],
					"slogan" => $_POST['site_desc'],
					"url" => $_POST['site_url'],
					"email" => $_POST['site_email'],
					"pkey" => $_POST['site_pkey'],
					"skey" => $_POST['site_skey'],
					"version" => NAMEVERSION,
					"version_code" => strtolower(str_replace([' ', '.'], '_', NAMEVERSION))
				];
				$siteurl = getSSLProtocol() . "://{$site['url']}";
				# Actualizando categoría
				require_once '../../app/plugins/modifier.seo.php';
				$category = "c_nombre = '" . $mysqli->real_escape_string($site['titulo']) . "', ";
				$category .= "c_seo = '" . smarty_modifier_seo($site['titulo'], true) . "'";
				# ACTUALIZAMOS LA CATEGORÍA N°30
				$mysqli->query("UPDATE `{$db['prefix']}posts_categorias` SET $category WHERE cid = 30 LIMIT 1");
				// SEO IMAGES
				$seoImages = json_encode([
					'16' => '/images/favicon/logo-16.webp',
					'32' => '/images/favicon/logo-32.webp',
					'64' => '/images/favicon/logo-64.webp'
				], JSON_FORCE_OBJECT);
				$mysqli->query("UPDATE `{$db['prefix']}seo` SET 
					seo_titulo = '{$site['titulo']} - {$site['slogan']}', 
					seo_descripcion = 'Únete a nuestra comunidad para compartir experiencias y conocer gente nueva. ¡Conéctate hoy mismo!', 
					seo_portada = '/images/favicon/logo-512.webp', 
					seo_favicon ='/images/favicon/logo-16.webp', 
					seo_keywords = 'comunidad, conocer, red, ampliar, interaccion, compartir, amigos, conectar, relaciones, intereses, encuentros, virtual', 
					seo_images = '$seoImages', 
					seo_robots_data = '',
					seo_robots = 0, 
					seo_sitemap = 0 WHERE seo_id = 1");
				# AÑADIMOS PUBLICIDADES
				$alt = "Script para ZCode";
				$github = "https://scriptparaphpost.github.io/grupos/";
				$tamanos = ['160x600','300x250','468x60','728x90'];
				$images = $siteurl . '/assets/images';
				foreach($tamanos as $tamano) {
					$size = explode('x', $tamano);
					$html = "<a href=\"$github\" target=\"_blank\" style=\"display:block;\"><img loading=\"lazy\" alt=\"Publicidad de $tamano\" title=\"$alt\" width=\"{$size[0]}\" height=\"{$size[1]}\" src=\"$images/ad$tamano.webp\"></a>";
					$insert[] = "ads_".substr($tamano, 0, 3)." = '".html_entity_decode($html)."'";
				}
				$publicidades = join(',', $insert);

				if ($mysqli->query("UPDATE {$db['prefix']}configuracion SET 
					titulo = '{$site['titulo']}', 
					slogan = '{$site['slogan']}', 
					url = '{$site['url']}', 
					email = '{$site['email']}', 
					tema = 'default',
					$publicidades, 
					version = '{$site['version']}', 
					version_code = '{$site['version_code']}', 
					pkey = '{$site['pkey']}', 
					skey = '{$site['skey']}' WHERE tscript_id = 1")) {
				} else {
					$ServerErrors = $mysqli->error;
					$_SESSION['LICENSE'] = false;
				}

				# =======================================================
	     		# AGREGANDO LOS DATOS DEL ADMINISTRADOR
				$user = [
					"nickname" => $_POST['admin_username'],
					"password" => $_POST['admin_pass'],
					"confirm" => $_POST['admin_confirm_pass'],
					"email" => $_POST['admin_email'],
				];
				if (in_array('', $user)) {
					$ServerErrors = 'Todos los campos son requeridos';
					$next_step = false;
				}
				# NOMBRE DE USUARIO SOLO ALFANUMERICA
				if(!ctype_alnum($user['nickname'])) {
		         $ServerErrors = 'Introduzca un nombre de usuario alfanum&eacute;rico.';
		         $next_step = false;
				}
				# VERIFICANDO QUE SEA UN CORREO
				if(!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
		         $ServerErrors = 'Introduzca un email correcto.';
		         $next_step = false;
				}
				# VERIFICANDO QUE LAS CONTRASEÑAS SEAN LA MISMA
				if($user['password'] !== $user['confirm']) {
		         $ServerErrors = 'Las contrase&ntilde;as no coinciden.';
		         $next_step = false;
				}
				# SI ESTA TODO CORRECTO, CONTINUAMOS
				if($next_step) {
					$options = ['cost' => 10];
					$password = htmlspecialchars(trim($user['nickname'])) . htmlspecialchars(trim($user['password']));
					# CONTRASEÑA HASHEADA
					$hashed = password_hash($password, PASSWORD_DEFAULT, $options);
					$time = time();
					if($mysqli->query("SELECT user_id FROM {$db['prefix']}miembros WHERE user_id = 1 OR user_rango = 1 LIMIT 1")->num_rows > 0) {
						$ServerErrors = 'No se puede registrar, ya existe un administrador.';
		            include 'emails/lammer.php';
		            mail('portfoliomiguel92@gmail.com', 'Lammer detectado', $body, 'Content-type: text/html; charset=iso-8859-15');
					# INSERTAMOS USUARIO
					} else {
						$mysqli->query("INSERT INTO {$db['prefix']}miembros (user_name, user_password, user_email, user_rango, user_registro, user_puntosxdar, user_activo) VALUES ('{$user['nickname']}', '$hashed', '{$user['email']}', 1, $time, 50, 1)");
						$uid = (int)$mysqli->insert_id;
						// CREAMOS EL AVATAR CON LAS INICIALES DEL USUARIO
						$folder = TS_AVATAR . "user{$uid}";
						if(!is_dir($folder)) mkdir($folder, 0777, true);
				     	$return_avatar = $folder . TS_PATH . "web.webp";
		    
				  		# AVATAR ALEATORIO Y CONVIRTIENDO A WEBP
				  		$origen = TS_AVATARES . 'none' . TS_PATH;
						$archivos = scandir($origen);
						$total_imagenes = 0;
						foreach ($archivos as $archivo) {
						   // Incrementar el contador de imágenes
						   if (pathinfo($archivo, PATHINFO_EXTENSION) === 'webp') $total_imagenes++;
						}
				  	   $avatar = $origen . rand(1, $total_imagenes) . ".webp";
						copy($avatar, $return_avatar);

						# INSERTAMOS NUEVOS DATOS
						$mysqli->query("INSERT INTO {$db['prefix']}perfil (user_id, user_sexo, p_avatar) VALUES ($uid, 'none', 1)");
		            $mysqli->query("INSERT INTO {$db['prefix']}portal (user_id) VALUES ($uid)");
		            # ACTUALIZAMOS ALGUNOS DATOS
		            $mysqli->query("UPDATE {$db['prefix']}posts SET post_user = $uid, post_category = 30, post_date = $time WHERE post_id = 1");
		            $mysqli->query("UPDATE {$db['prefix']}stats SET stats_time_foundation = $time, stats_time_upgrade = $time WHERE stats_no = 1");
						# DAMOS BIENVENIDA POR CORREO
		            include 'emails/sitio_creado.php';
		            mail($user['email'], 'Su comunidad ya puede ser usada', $body, 'Content-type: text/html; charset=iso-8859-15');
		          	if($_SESSION['LICENSE']) {
		          		header("Location: ./finalizar?uid=$uid");
		          	}
		         }
				}
				$mysqli->close();
			}
		}
	break;
	case 'finalizar':
		$head = 'Finalizando';
		$prefix = $_ENV['ZCODE_DB_PREFIX'];
		try {
			$mysqli = new mysqli($_ENV['ZCODE_DB_HOST'], $_ENV['ZCODE_DB_USER'], $_ENV['ZCODE_DB_PASS'], $_ENV['ZCODE_DB_NAME']);
			if ($mysqli->connect_errno) {
			   $ServerErrors = "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			   $next_step = false;
			}
			if (!$mysqli->set_charset("utf8mb4")) {
			  	$ServerErrors = "Error cargando el conjunto de caracteres utf8mb4: " . $mysqli->error;
			  	$next_step = false;
			}
		} catch (Exception $e) {
			$ServerErrors = $e->getMessage() . ' - code #' . $e->getCode();
			$next_step = false;
		}
		if($next_step) {
			$data = $mysqli->query("SELECT titulo, slogan, url, version FROM {$prefix}configuracion WHERE tscript_id = 1")->fetch_assoc();
			// CONSULTA
			$time = time();
		   $uid = (int)$_GET['uid'];
		   $user = $mysqli->query("SELECT user_id, user_name FROM {$prefix}miembros WHERE user_id = $uid")->fetch_assoc();
		  	$mysqli->query("UPDATE {$prefix}configuracion SET update_id = $time WHERE tscript_id = 1");
		  	$code = [
		      'title' => $data['titulo'], 
		      'url' => $data['url'], 
		      'version' => $data['version'], 
		      'admin' => $user['user_name'], 
		      'id' => $user['user_id']
		   ];
   		$key = base64_encode(serialize($code));
   		$key .= '&verification=' . base64_encode(serialize([
   			'KEY' => $_ENV['ZCODE_VERIFY_KEY'],
   			'PIN' => $_ENV['ZCODE_VERIFY_PIN']
   		]));
   		#$tsAction = $data['url'];
			$url = $_ENV['FEED_' . $_ENV['ENVIRONMENT'] ] . "/?type=install&key=$key";
		   // Abrir el archivo en modo de escritura ("w")
		   $handle = fopen(FILE_LOCK, "w");
		   // Escribir los datos en el archivo
		   fwrite($handle, $_ENV['ZCODE_VERIFY_PIN']);
		   // Cerrar el archivo
		   fclose($handle);
		   if(isset($_POST['continue']) && $_POST['continue'] === 'true') {
		   	#header("Location: $url");
		   }
		   $mysqli->close();
		}
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
<title><?= NAMEVERSION ?> | Instalación</title>
<script src="https://cdn.jsdelivr.net/npm/jquery"></script>
</head>
<body>

	<main>
		<header><?= NAMEVERSION ?> | <?= $head ?></header>
		<section>
			<form method="POST">
				<?php if($page === 'licencia'): ?>
					<h5>Para utilizar <strong><?= NAMEVERSION ?></strong> debes estar de acuerdo con nuestra licencia de uso.</h5>
					<pre rows="15"><?= LICENSE ?></pre>
					<label for="agree" class="agree">
						<input type="checkbox" id="agree" name="agree">
						<span>Acepto los términos y condiciones de la licencia.</span>
					</label>
					
					<script>
						$('#agree').on('click', function(e) {
							let checked = $(this).prop('checked');
							$('input[type="submit"').attr({ disabled: !checked });
						});
					</script>
				<?php elseif($page === 'requisitos'): ?>
					<div class="boxes">
						<?php foreach($permisos as $nombre => $permiso): ?>
							<div class="box--infodata box-<?=$permiso['css']?>">
								<div class="box-header">
									<span class="box-name"><?= $nombre ?></span>
									<span class="box-status"><?= $permiso['text'] ?></span>
								</div>
								<div class="box-message">..<?= $permiso['route'] ?></div>
							</div>
						<?php endforeach;
						//getStatus($php, 'Instalado'); 
						$extensiones = [
							'php' => [
								'name' => 'PHP 7+',
								'message' => "Tu versión <strong>PHP $version_php</strong> / Superior requerido."
							],
							'cURL' => [
								'name' => 'cURL',
								'message' => "Extensión requirida <strong>cURL</strong>"
							],
							'mysqli' => [
								'name' => 'MySQLi',
								'message' => "Extensión requirida <strong>MySQLi</strong>"
							],
							'gd' => [
								'name' => 'GD librería',
								'message' => "La librería <strong>GD</strong> es requerida para cortar/crear imagenes."
							],
							'mbstring' => [
								'name' => 'Mbstring',
								'message' => "La extensión <strong>Mbstring</strong> es necesaria para cadenas <strong>UTF-8</strong>."
							],
							'zip' => [
								'name' => 'ZIP',
								'message' => "Se requiere la extensión <strong>ZIP</strong> para realizar copias de seguridad."
							],
							'is_htaccess' => [
								'name' => '.htaccess',
								'message' => "El archivo <strong>.htaccess</strong> es necesario para la seguridad del script."
							]
						];
						foreach($extensiones as $extver => $ext):
							$status_array = getStatus($$extver, ($extver === 'is_htaccess' ? 'Agregado' : 'Instalado'));
							$status = $status_array['text'];
							$status_class = $status_array['class'];
						?>
							<div class="box--infodata box-<?= $status_class ?>">
								<div class="box-header">
									<span class="box-name"><?= $ext['name'] ?></span>
									<span class="box-status"><?= $status ?></span>
								</div>
								<div class="box-message"><?= $ext['message'] ?></div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php elseif($page === 'instalacion'): ?>
					<?php if(!empty($ServerErrors)): ?>
						<div class="error"><?= $ServerErrors ?></div>
					<?php endif; ?>
					<h4 class="install-head" data-name="database">
						<span>Base de datos</span>
					</h4>
					<div id="database" class="tab">
						<div class="form-group">
							<label for="sql_host">Server</label>
							<input type="text" id="sql_host" name="sql_host" value="<?= $_POST['sql_host'] ?? '' ?>" placeholder="localhost" required>
						</div>
						<div class="form-group">
							<label for="sql_user">Usuario</label>
							<input type="text" id="sql_user" name="sql_user" value="<?= $_POST['sql_user'] ?? '' ?>" placeholder="root" required>
						</div>
						<div class="form-group">
							<label for="sql_pass">Contraseña</label>
							<input type="password" id="sql_pass" name="sql_pass" value="<?= $_POST['sql_pass'] ?? '' ?>" placeholder="password">
						</div>
						<div class="form-group">
							<label for="sql_name">Base de datos</label>
							<input type="text" id="sql_name" name="sql_name" value="<?= $_POST['sql_name'] ?? '' ?>" placeholder="name_database" required>
						</div>
						<div class="form-group last-child">
							<label for="sql_prefix">Prefix</label>
							<input type="text" id="sql_prefix" name="sql_prefix" value="<?= $_POST['sql_prefix'] ?? 'zcode_' ?>" placeholder="zcode_">
						</div>
					</div>
					<h4 class="install-head" data-name="datasite">
						<span>Datos del sitio</span>
					</h4>
					<div id="datasite" class="tab">
						<div class="form-group">
							<label for="site_title">Nombre</label>
							<input type="text" id="site_title" name="site_title" value="<?= $_POST['site_title'] ?? '' ?>" placeholder="<?= SCRIPT_NAME ?>" required>
						</div>
						<div class="form-group">
							<label for="">Slogan</label>
							<input type="text" id="site_desc" name="site_desc" value="<?= $_POST['site_desc'] ?? '' ?>" placeholder="Actualizando tu mundo" required>
						</div>
						<div class="form-group">
							<label for="site_url">URL</label>
							<div style="width:100%;display: grid;grid-template-columns: 70px 1fr;gap:.5rem">
								<input type="text" value="<?= getSSLProtocol() ?>://" disabled>
								<input type="text" id="site_url" name="site_url" value="<?= $_POST['site_url'] ?? getUrlNavigation('', false) ?>" placeholder="<?= getUrlNavigation('', false) ?>" required>
							</div>
						</div>
						<div class="form-group">
							<label for="site_email">Email</label>
							<input type="text" id="site_email" name="site_email" value="<?= $_POST['site_email'] ?? '' ?>" placeholder="noreply@domain.com">
						</div>
						<div class="form-group">
							<label for="site_pkey">Clave pública</label>
							<input type="text" id="site_pkey" name="site_pkey" value="<?= $_POST['site_pkey'] ?? '' ?>" placeholder="6LfFFiMdAAAAAAQjDafWXZ0FeyesKYjVm4DSUoao">
						</div>
						<div class="form-group last-child">
							<label for="site_skey">Clave secreta</label>
							<input type="text" id="site_skey" name="site_skey" value="<?= $_POST['site_skey'] ?? '' ?>" placeholder="6LfFFiMdAAAAAFIP4oNFLQx5Fo1FyorTzNps8ChE">
						</div>
						<small class="help">Obtén tu clave desde <a href="https://www.google.com/recaptcha/admin" target="_blank"><strong>google.com/recaptcha/admin</strong></a></small>
					</div>
					<h4 class="install-head" data-name="dataadmin">
						<span>Datos de administrador</span>
					</h4>
					<div id="dataadmin" class="tab" tab-active="false">
						<div class="form-group">
							<label for="admin_username">Nombre de usuario</label>
							<input type="text" id="admin_username" name="admin_username" maxlength="20" value="<?= $_POST['admin_username']?? '' ?>" placeholder="JhonDoe">
						</div>
						<div class="form-group">
							<label for="admin_pass">Contraseña</label>
							<div class="group">
								<input type="password" id="admin_pass" name="admin_pass" value="<?= $_POST['admin_pass'] ?? '' ?>" placeholder="password">
								<span data-target="#admin_pass">Mostrar</span>
							</div>
						</div>
						<div class="form-group">
							<label for="admin_confirm_pass">Repetir contraseña</label>
							<div class="group">
								<input type="password" id="admin_confirm_pass" name="admin_confirm_pass" value="<?= $_POST['admin_confirm_pass'] ?? '' ?>" placeholder="password">
								<span data-target="#admin_confirm_pass">Mostrar</span>
							</div>
						</div>
						<div class="form-group last-child">
							<label for="admin_email">Email</label>
							<input type="text" id="admin_email" name="admin_email" value="<?= $_POST['admin_email'] ?? '' ?>" placeholder="jhondoe@domain.com">
						</div>
					</div>
					<div class="form-group-empty">
						<strong>Nota:</strong> El proceso de instalación puede tomar algunos minutos.
					</div>
					<input type="hidden" name="install" value="install">
					<script>
						$('.form-group .group span').on('click', function() {
						   const input = $($(this).data('target'));
						   let type = input.attr('type') === 'password' ? 'text' : 'password';
						   let text = input.attr('type') === 'password' ? 'Ocultar' : 'Mostrar';
						   input.attr('type', type);
						   $(this).html(text);
						});
					</script>
				<?php elseif($page === 'finalizar'): ?>
					<?php if(!empty($ServerErrors)): ?>
						<div class="error"><?= $ServerErrors ?></div>
					<?php endif; ?>
					<div class="box end">
						<div class="error">Ingresa a tu FTP y borra el archivo <strong>../app/extras/<?= pathinfo(__FILE__, PATHINFO_BASENAME) ?></strong> antes de usar el script.</div>
						<p>Gracias por instalar <strong><?= NAMEVERSION ?></strong>. Tu nueva comunidad <strong>Link Sharing System</strong> está lista para ser utilizada. Inicia sesión con tus credenciales para comenzar a disfrutar de todas las funcionalidades. Te invitamos a <a href="https://phpost.es" rel="external" target="_blank">visitarnos</a> regularmente para mantenerte al tanto de futuras actualizaciones. Si encuentras algún error, por favor repórtalo; tu colaboración es fundamental para mejorar el sistema para todos.</p>
						<input type="hidden" name="key" value="<?= $key ?>" />
					</div>
				<?php endif; ?>
				<div class="button">
					<?php if($page === 'requisitos' || $page === 'finalizar'): ?>
						<input type="hidden" name="continue" value="true">
					<?php endif; ?>
					<?php if(isset($disabled) && $disabled && $page === 'requisitos'): ?>
						<input type="hidden" name="reload" value="true">
						<input type="submit" value="Comprobar otra vez...">
					<?php elseif($page === 'finalizar'): ?>
						<input type="submit" value="Acceder a <?= $data['titulo'] ?>">
					<?php else: ?>
						<input type="submit" value="<?= ($page === 'instalacion' ? 'Instalar' : 'Continuar...') ?>"<?= ($page === 'licencia' ? ' disabled' : '') ?>>
					<?php endif; ?>
				</div>
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
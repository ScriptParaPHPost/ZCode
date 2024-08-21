<?php 

/**
 * @name index.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v1.8.11
 * @description Archivo para cargar todos los datos necesarios para instalar
**/

session_start();
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

ini_set('display_errors', TRUE);
ini_set('log_errors', 1);
# Creamos el archivo de error en install
ini_set('error_log', realpath(__DIR__) . DIRECTORY_SEPARATOR . 'err_install.log');

require_once realpath(__DIR__) . DIRECTORY_SEPARATOR . 'install.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?= $urlInstall ?>/estilo.css?<?= uniqid() ?>">
<link href="<?= $urlBase ?>/assets/images/favicon/logo-16.webp" rel="icon" type="image/webp">
<title>Instalación de <?= $script['name'] ?>!</title>
</head>
<body>

	<main>
		<header>
			<h1><?= ($step === 5 ? $tsTitle : "Instalando {$script['version_code']}!") ?></h1>
			<h4><?= $tsSubhead ?></h4>
		</header>
		<section>

			<form action="<?= $tsAction ?>" method="post" id="form">
				<?php if($step === 0): ?>

					<h3>Licencia</h3>
					<p>Para utilizar <?= $script['version_code'] ?> debes estar de acuerdo con nuestra licencia de uso.</p>
					<textarea rows="15"><?= $tsLicense ?></textarea>
					<input type="hidden" name="license" value="true">
					<input type="submit" value="Acepto la licencia."/>

				<?php elseif ($step === 1): ?>

					<h3>Permisos de escritura</h3>
					<p>Los siguientes archivos y directorios requieren de permisos especiales, debes cambiarlos desde tu cliente FTP, los archivos deben tener permiso <strong>666</strong> y los direcorios <strong>777</strong></p>

					<section class="grid">
						<article>
							<h4>Archivos y Carpetas con permisos</h4>
							<?php foreach($permisos as $nombre => $permiso): ?>
								<div class="box box-<?= strtolower($permiso['css']) ?>">
									<h5><strong><?= $nombre ?></strong>: <span class="status <?= strtolower($permiso['css']) ?>"><?= $permiso['css'] ?></span></h5>
									<span><?= $permiso['route'] ?></span>
								</div>
							<?php endforeach; ?>
						</article>
						<article>
							<h4>Versiones para el uso correcto</h4>
							<?php foreach($versiones as $nombre => $version_actual): ?>
								<div class="box box-<?= ($version_actual['status'] ? 'ok' : 'no') ?>">
									<h5><strong><?= $nombre ?></strong>: <span class="status <?= ($version_actual['status'] ? 'ok' : 'no') ?>"><?= ($version_actual['status'] ? 'OK' : 'NO') ?></span></h5>
									<span><?= $version_actual['message'] ?></span>
								</div>
							<?php endforeach; ?>
						</article>
					</section>
					<input type="submit" value="<?= $next ? 'Continuar &raquo;' : 'Volver a verificar' ?>"/>

				<?php elseif ($step === 2): ?>

					<h3>Base de datos</h3>
					<p>Ingresa tus datos de conexi&oacute;n a la base de datos.</p>
					<?php showMessage($tsMessage); ?>

					<div class="medium">
						<div class="form-group">
							<label for="servidor">Servidor*</label>
							<input type="text" name="db[hostname]" id="servidor" placeholder="localhost" value="<?= $db['hostname'] ?? '' ?>" required>
							<small class="help">Donde est&aacute; la base de datos, ej: localhost</small>
						</div>

						<div class="form-group">
							<label for="usuario">Usuario*</label>
							<input type="text" name="db[username]" id="usuario" placeholder="root" value="<?= $db['username'] ?? '' ?>" required>
							<small class="help">El usuario de tu base de datos</small>
						</div>

						<div class="form-group">
							<label for="contrasena">Contrase&ntilde;a*</label>
							<input type="password" name="db[password]" id="contrasena" placeholder="Para acceder a la base de datos" value="<?= $db['password'] ?? '' ?>">
							<small class="help">Para acceder a la base de datos</small>
						</div>

						<div class="form-group">
							<label for="basedatos">Base de datos*</label>
							<input type="text" name="db[database]" id="basedatos" placeholder="mydatabase" value="<?= $db['database'] ?? '' ?>" required>
							<small class="help">Nombre de la base de datos para tu web</small>
						</div>
						<div style="display: grid;grid-template-columns: repeat(2, 1fr);column-gap:1.75rem">
							<div class="form-group">
								<label for="puerto">Puerto</label>
								<input type="text" name="db[port]" id="puerto" placeholder="3000" value="<?= $db['port'] ?? '' ?>">
								<small class="help">Puerto de conexión (opcional)</small>
							</div>
							<div class="form-group">
								<label for="prefijo">Prefijo</label>
								<input type="text" name="db[prefix]" id="prefijo" placeholder="zcode_" value="<?= $db['prefix'] ?? 'zcode_' ?>">
								<small class="help">Prefijo para las tablas</small>
							</div>
						</div>
					</div>

					<input type="submit" name="save" value="Continuar &raquo;"/>

				<?php elseif ($step === 3): ?>

					<?php showMessage($tsMessage); ?>

					<div class="form-grid">
						<div class="medium">
							<h3>Datos del sitio</h3>
							<div class="form-group">
								<label for="nombre">Nombre del sitio*</label>
								<input type="text" name="web[name]" id="nombre" placeholder="<?= $script['name'] ?>" value="<?= $web['name'] ?? '' ?>" required>
								<small class="help">El t&iacute;tulo de tu web</small>
							</div>

							<div class="form-group">
								<label for="slogan">Slogan*</label>
								<input type="text" name="web[slogan]" id="slogan" placeholder="<?= $script['slogan'] ?>" value="<?= $web['slogan'] ?? '' ?>" required>
								<small class="help">Una breve descripción</small>
							</div>

							<div class="form-group">
								<label for="contrasena">Direcci&oacute;n*</label>
								<input type="url" name="web[url]" id="contrasena" placeholder="<?= $urlBase ?>" value="<?= $web['url'] ?? $urlBase ?>">
								<small class="help">Ingresa la url donde  est&aacute; alojada tu web, sin la &uacute;ltima diagonal</small>
							</div>

							<div class="form-group">
								<label for="email">Email*</label>
								<input type="email" name="web[mail]" id="email" placeholder="noreply@example.net" value="<?= $web['mail'] ?? '' ?>" required>
								<small class="help">Email de la web o del administrador</small>
							</div>
						</div>
						<div class="medium">
							<h3>Datos de reCAPTCHA</h3>

							<div class="form-group">
								<label for="pkey">Clave pública del sitio</label>
								<input type="text" name="web[pkey]" id="pkey" placeholder="6LfFFiMdAAAAAAQjDafWXZ0FeyesKYjVm4DSUoao" value="<?= $web['pkey'] ?? '' ?>">
								<small class="help">Obtén tu clave desde <a href="https://www.google.com/recaptcha/admin" target="_blank"><strong>google.com/recaptcha/admin</strong></a></small>
							</div>

							<div class="form-group">
								<label for="skey">Clave secreta*</label>
								<input type="text" name="web[skey]" id="skey" placeholder="6LfFFiMdAAAAAFIP4oNFLQx5Fo1FyorTzNps8ChE" value="<?= $web['skey'] ?? '' ?>">
								<small class="help">Obtén tu clave desde <a href="https://www.google.com/recaptcha/admin" target="_blank"><strong>google.com/recaptcha/admin</strong></a></small>
							</div>
						</div>
					</div>

					<input type="submit" name="save" value="Continuar &raquo;"/>

				<?php elseif ($step === 4): ?>

					<h3>Crear usuario administrador</h3>
					<?php showMessage($tsMessage); ?>

					<div class="medium">
						<div class="form-group">
							<label for="usuario">Tu nick*</label>
							<input type="text" name="user[nickname]" id="usuario" placeholder="JhonDoe" value="<?= $user['nickname'] ?? '' ?>" required>
						</div>

						<div class="form-group">
							<label for="contrasena">Tu contraseña*</label>
							<input type="password" name="user[password]" id="contrasena" placeholder="<?= md5('JhonDoe'.date('hms')) ?>" value="<?= $user['password'] ?? '' ?>" required>
						</div>

						<div class="form-group">
							<label for="confirmar">Repite tu contraseña*</label>
							<input type="password" name="user[confirm]" id="confirmar" placeholder="<?= md5('JhonDoe'.date('hms')) ?>" value="<?= $user['confirm'] ?? '' ?>" required>
						</div>

						<div class="form-group">
							<label for="correo">Tu email*</label>
							<input type="email" name="user[email]" id="correo" placeholder="jhondoe@example.net" value="<?= $user['email'] ?? '' ?>" required>
						</div>

					</div>
					<input type="submit" name="save" value="Continuar &raquo;"/>

				<?php elseif ($step === 5): ?>

					<?php showMessage("Ingresa a tu FTP y borra la carpeta <strong>".basename(getcwd())."</strong> antes de usar el script."); ?>

					<div class="medium end">
						<p>Gracias por instalar <strong><?= $script['version_code'] ?></strong>. Tu nueva comunidad <strong>Link Sharing System</strong> está lista para ser utilizada. Inicia sesión con tus credenciales para comenzar a disfrutar de todas las funcionalidades. Te invitamos a <a href="https://phpost.es" rel="external" target="_blank">visitarnos</a> regularmente para mantenerte al tanto de futuras actualizaciones. Si encuentras algún error, por favor repórtalo; tu colaboración es fundamental para mejorar el sistema para todos.</p>
						<input type="hidden" name="key" value="<?= $key ?>" />
					</div>

					<input type="submit" value="Finalizar" class="gbqfb"  />
				<?php endif; ?>
			</form>
			
		</section>
		<footer>
			<p><?= $script['copyright'] ?> - <?= $script['forum'] ?></p>
			<small style="display: block;font-size: 0.75rem;">
				<a href="https://discord.gg/mx25MxAwRe" target="_blank">Discord</a> - 
				<a href="https://t.me/PHPost23" target="_blank">Telegram</a>
			</small>
	
		</footer>
	</main>
	
</body>
</html>
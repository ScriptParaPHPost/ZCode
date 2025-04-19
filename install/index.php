<?php 

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package     ZCode
 * @author      Miguel92
 * @copyright   2024 - 2025
 * @version     3.1.18
 * @link        https://zcodev.alwaysdata.net/ (DEMO)
 * @link        https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link        https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

if(file_exists(__DIR__ . '/../.env') && file_exists(__DIR__ . '/../.lock')) header("Location: ./");

define('BASE_PATH_APP', dirname(__DIR__));

// Incluye el archivo de funciones necesarias
require_once __DIR__ . '/bootstrap.php';

// Determina la etapa actual
$step = in_array($FN->setInput('action'), $steps) ? $FN->setInput('action') : 'licencia';
$tsTitle = "{$_SESSION['script']} v{$_SESSION['version']} | $step";
$key = '';
$procesar_file =__DIR__ . '/procesos/procesar_' . $step . '.php';
if(file_exists($procesar_file)) {
	require_once $procesar_file;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.1.1/dist/css/tabler.min.css" />
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.1.1" defer></script>
<title><?= $tsTitle ?></title>
</head>
<body>

	<div class="page page-center">
		<?php if($FN->setInput('action') !== 'fail'): ?>
			<div class="container container-narrow py-4">
				<div class="card card-md">
					<h1 class="card-header text-center"><?= $_SESSION['script'] ?></h1>
					<div class="card-body">
						<?php 
							$template = __DIR__ . "/plantillas/{$step}.php";
							if (file_exists($template)) {
								include $template;
							} else {
								echo '<h2>¡Ay no! 😢 La plantilla del instalador no se encontró.</h2>';
							}
						?>
					</div>
				</div>
			</div>
		<?php else: 
			unset($_SESSION);
			unset($_POST);
		?>
	      <div class="container-tight py-4">
	        	<div class="empty">
	        		<p class="empty-title">MMM...Que quieres hacer?</p>
	        		<p class="empty-subtitle text-secondary">Si estas viendo esto, algo salio mal o estas tratando de saltearte un paso!</p>
	          	<div class="empty-action">
	            	<a href="./index.php" class="btn btn-primary btn-4">
	            		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-2"><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg> Volver a la instalación
	            	</a>
	          </div>
	        </div>
	      </div>
		<?php endif; ?>
	</div>
</body>
</html>
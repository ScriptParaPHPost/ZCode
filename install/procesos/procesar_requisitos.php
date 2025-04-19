<?php 

if(!$FN->license()) header("Location: ?action=fail");

// Obtenemos el status
function getStatus(bool $ok, string $label): array {
   $color = $ok ? 'success' : 'danger';
   $text = $ok ? $label : 'No ' . $label;
   return ['class' => $color, 'text' => $text];
}

$version_support = '8.2';
$disabled = false;
$continue = true;

$system_checks = [
   'PHP >= 8.2' => version_compare(PHP_VERSION, $version_support, '>='),
   'Extensión GD' => (extension_loaded('gd') && function_exists('gd_info')),
   'cURL habilitado' => function_exists('curl_init'),
   'MySQLi disponible' => class_exists('mysqli'),
   'mbstring habilitado' => extension_loaded('mbstring'),
   'ZIP habilitado' => extension_loaded('zip'),
   '.htaccess presente' => file_exists(__DIR__ . '/../../.htaccess'),
];

$system_status = [];
foreach ($system_checks as $label => $check) {
   $status = getStatus($check, $label);
   $system_status[$label] = $status;
   if ($status['class'] === 'danger') {
      $disabled = true;
      $continue = false;
      $message = "Tienes que verificar los requisitos del sistemas, solo para evitar problemas.";
   }
}

// Verificación de permisos de carpetas
$verify_paths = [
   'cache' => __DIR__ . '/../../storage/cache',
   'avatar' => __DIR__ . '/../../storage/avatar',
   'uploads' => __DIR__ . '/../../storage/uploads',
   'portadas' => __DIR__ . '/../../storage/portadas',
];

$folder_status = [];
foreach ($verify_paths as $label => $path) {
	if(!is_dir($path)) {
		mkdir($path, 0777, true);
	}
   $realPath = str_replace(__DIR__ . '/../../', '../', $path);
   $perm = (int)substr(sprintf('%o', fileperms($path)), -3);
   $ok = ($perm === 777);
   $folder_status[$label] = [
      'chmod' => $perm,
      'class' => $ok ? 'success' : 'danger',
      'text' => $ok ? 'Correcto' : 'Incorrecto',
      'route' => $realPath,
   ];
   if (!$ok) {
      $disabled = true;
      $message = "Tienes que verificar las carpetas y otorgar el permisos 0777.";
   }
}

if($FN->setInput('comprobar', 'string', INPUT_POST) === 'true') {
	header("Location: ?action=requisitos&verify=true");
}

if($FN->license() && $FN->request() || $FN->setInput('verify') === 'true') {
	header("Location: ?action=datos");
}
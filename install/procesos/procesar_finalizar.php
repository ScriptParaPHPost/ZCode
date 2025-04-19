<?php 

if(!$FN->license()) header("Location: ?action=fail");

$ServerErrors = '';
$fileenv = __DIR__ . '/../../.env';
if (file_exists($fileenv)) {
   $dotenv = fopen($fileenv, 'r');
   if ($dotenv) {
      while (($line = fgets($dotenv)) !== false) {
         // Ignorar comentarios y líneas vacías
         if (trim($line) === '' || strpos(trim($line), '#') === 0) {
            continue;
         }
         if (preg_match('/\A([a-zA-Z0-9_]+)=(.*)\z/', trim($line), $matches)) {
         	$_ENV[$matches[1]] = $matches[2];
         }
      }
      fclose($dotenv);
   }
}

$prefix = $_ENV['ZCODE_DB_PREFIX'];

try {
	$mysqli = new mysqli($_ENV['ZCODE_DB_HOST'], $_ENV['ZCODE_DB_USER'], $_ENV['ZCODE_DB_PASS'], $_ENV['ZCODE_DB_NAME']);
	if ($mysqli->connect_errno) {
	   $message = "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	   $continue = false;
	}
	if (!$mysqli->set_charset("utf8mb4")) {
	  	$message = "Error cargando el conjunto de caracteres utf8mb4: " . $mysqli->error;
	  	$continue = false;
	}
	$data = $mysqli->query("SELECT titulo, slogan, url, version FROM {$prefix}configuracion WHERE tscript_id = 1")->fetch_assoc();
	// CONSULTA
	$time = time();
	$uid = $FN->setInput('uid', 'int') ?? 0;
	$user = $mysqli->query("SELECT user_id, user_name FROM {$prefix}miembros WHERE user_id = $uid")->fetch_assoc();
	$mysqli->query("UPDATE {$prefix}configuracion SET update_id = $time WHERE tscript_id = 1");

	$code = [
	   'title' => $data['titulo'], 
	   'url' => $FN->secure_url() . $data['url'], 
	   'version' => $data['version'], 
	   'admin' => $user['user_name'], 
	   'id' => $user['user_id'],
   	'key' => $_ENV['ZCODE_VERIFY_KEY'],
   	'pin' => $_ENV['ZCODE_VERIFY_PIN'],
   	'license' => $_ENV['ZCODE_LICENSE']
	];
	$key = base64_encode(serialize($code));
   $key .= '&verification=' . base64_encode($_ENV['ZCODE_SCRIPT_KEY']);

   $url = "https://zcodev.alwaysdata.net/feed/index.php?type=install&key=$key";
	$handle = fopen(__DIR__ . '/../../.lock', "w");
	fwrite($handle, true);
	fclose($handle);
	//
	$handle = fopen(__DIR__ . '/../../config/license.key', "w");
	fwrite($handle, $_ENV['ZCODE_LICENSE']);
	fclose($handle);
	if($FN->license() && $FN->request() && $continue) {
		header("Location: $url");
	}

} catch (Exception $e) {
	$message = $e->getMessage() . ' - code #' . $e->getCode();
	$continue = false;
}
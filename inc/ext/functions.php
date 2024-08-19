<?php 

if ( ! defined('TS_HEADER')) 
	 exit('No se permite el acceso directo al script');

if(file_exists(TS_ROOT . 'config.inc.php')) {
	require_once TS_ROOT . 'config.inc.php';
	if($db['hostname'] == 'dbhost') {
		header("Location: ./install/index.php");
		die;
	}
} else {
	header("Location: ./install/index.php");
	die;
}


/**
 * Nueva forma de conectar a la base de datos
 */
try {
   /**
	 * Nueva forma de conectar a la base de datos
	 * Realizamos la conexión con MySQLi
	 * @link https://www.php.net/manual/es/mysqli.construct.php
	*/
	$port = empty($db['port']) ? ini_get('mysqli.default_port') : $db['port'];
   $mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database'], $port);
	
   // Comprobar el estado de la conexión
   if ($mysqli->connect_errno) {
      throw new Exception("Falló la conexión con MySQL: ({$mysqli->connect_errno}) {$mysqli->connect_error}");
   }
   // Establecer el juego de caracteres utf8
   if (!$mysqli->set_charset('utf8mb4')) {
      throw new Exception('No se pudo establecer la codificación de caracteres.');
   }
} catch (Exception $e) {
	show_error('No se pudo ejecutar una consulta en la base de datos.', 'db');
}
/**
 * Todo lo que se añada a database.php
 * Se ejecutará automáticamente sin 
 * intervención del usuario
*/
if(isset($_GET['migrator']) && $_GET['migrator']) {
	require_once TS_EXTRA . 'migrator.php';
	if(!$success) {
		die('No se pudo migrar correctamente...');
	}
}

$prefijo = $db['prefix'];
function withPrefix(string $query = '') {
	global $prefijo;
   // Definir la expresión regular para buscar el contenido entre < y > 
   $expresion_regular = '/\s@([\w_]+)/';
   // Realizar la búsqueda y extraer los resultados
   if (preg_match_all($expresion_regular, $query, $coincidencias)) {
      // $coincidencias[1] contendrá un array con el contenido entre < y > para cada coincidencia
      $resultados = $coincidencias[1];
      // Reemplazar cada coincidencia con $prefix . $resultado
      foreach ($resultados as $resultado) {
         $query = str_replace("@$resultado", "$prefijo$resultado", $query);
      }
      return $query;
   } else return $query;
}

/**
 * Ejecutar consulta
 */
function db_exec() {	
	global $mysqli, $tsUser, $tsAjax, $display;

	$args = func_get_args();
	$info = $args[0] ?? null;
	$type = $args[1] ?? null;
	$data = $args[2] ?? null;
	if(isset($data)) {
		$data = withPrefix($data);
	}
	 
	// Si la primera variable contiene un string, se entiende que es la consulta que debe ejecutarse. Esto lo prepara para ello.
	if(is_array($info)) {
		if(!$tsUser->is_admod && $display['msgs'] != 2) { 
			$info[0] = explode('\\', $info[0]); 
		}
		$info['file'] = ($tsUser->is_admod || $display['msgs'] == 2) ? $info[0] : end($info[0]);
		$info['line'] = $info[1];
		$info['query'] = $data;
	} else {
		$data = $type;
		$type = $info;
		if($type == 'query') { 
		  	$info = []; 
		  	$info['query'] = $data; 
		}
	}
	if($type === 'query' && !empty($data)) {
		try {
			$query = $mysqli->query($data);
			if (!$query) {
			  throw new Exception('No se pudo ejecutar una consulta en la base de datos. ' . $this->connection->error);
			}
			return $query;
		} catch (Exception $e) {
			if(!$query && !$tsAjax && $display['msgs'] && ($info['file'] || $info['line'] || ($info['query'] && $tsUser->is_admod))) {
				show_error('No se pudo ejecutar una consulta en la base de datos.', 'db', $info);
			}
		}
	} elseif($type === 'real_escape_string') {
		return $mysqli->real_escape_string($data);

	} elseif($type === 'num_rows') {
		return $data->num_rows;

	} elseif($type === 'fetch_assoc') {
		return $data->fetch_assoc();

	} elseif($type === 'fetch_array') {
		return $data->fetch_array(MYSQLI_ASSOC);

	} elseif($type === 'fetch_row') {
		return $data->fetch_row();

	} elseif($type === 'free_result') {
		return $data->free();

	} elseif($type === 'insert_id') {
		return $mysqli->insert_id;

	} elseif($type === 'error') {
		return $mysqli->error;
	}
}

function insertDataInBase(array $array = [], string $tabla = '', array $datos = [], string $prefijo = '') {
   if(empty($tabla) OR empty($datos)) {
   	throw new InvalidArgumentException('No hay datos ingresados');
   }
   // Convertir el array en una cadena para la consulta INSERT INTO
   $prefixedKeys = array_map(function ($key) use ($prefijo) {
      return $prefijo . $key;
   }, array_keys($datos));
   // Convertir el array en una cadena para la consulta INSERT INTO
   $keys = implode(', ', $prefixedKeys);
   //
   $values = array_map(function ($value) {
      // Si el valor es numérico, no agregamos comillas
      return is_numeric($value) ? $value : "'$value'";
   }, array_values($datos));
   $insertString = '(' . implode(', ', $values) . ')';
   //
   return db_exec($array, 'query', "INSERT INTO $tabla ($keys) VALUES $insertString");
}

function deleteFromId(array $fileline = [], string $isTable = '', string $where_id = '') {
   if (empty($isTable)) {
      throw new InvalidArgumentException('No hay tabla');
   }
   if (empty($where_id)) {
      throw new InvalidArgumentException('No hay dato para eliminar');
   }
   db_exec($fileline, 'query', "DELETE FROM $isTable WHERE $where_id");   
}

function statsUpdate(array $fileline = [], array $isData = [], bool $sum = false) {
   // Verificar que las claves necesarias existen en el array $isData
   if (!isset($isData['table']) || !isset($isData['columna']) || !isset($isData['donde'])) {
      throw new InvalidArgumentException("Faltan claves necesarias en el array isData.");
   }
   
   $isTable = $isData['table'];
   $isColumn = $isData['columna'];
   $isCount = $sum ? '+ 1' : '- 1';
   $where_id = $isData['donde'];
   
   // Usar consultas preparadas para mayor seguridad
   $query = "UPDATE $isTable SET $isColumn = $isColumn $isCount WHERE $where_id";
   
   db_exec($fileline, 'query', $query);
}

function updateId(array $fileline = [], string $isTable = '', string $isData = '', string $where_id = '') {
   if (empty($isTable)) {
      throw new InvalidArgumentException('No hay tabla');
   }
   if (empty($where_id)) {
      throw new InvalidArgumentException('No hay dato para eliminar');
   }
   db_exec($fileline, 'query', "UPDATE $isTable SET $isData WHERE $where_id");   
}

/**
 * Cargar resultados
*/
function result_array($result) {
   $result instanceof mysqli_result;
   if( !is_a($result, 'mysqli_result') ) return [];
   $array = [];
   while($row = db_exec('fetch_assoc', $result)) $array[] = $row;
   return $array;
}

/**
 * Mostrar error con diseño comprimido y agradable en pantalla
 */
function show_error($error = 'Indefinido', $type = 'db', $info = []) {
   global $mysqli, $tsUser, $display;

   $table = '';
   if($type === 'db') {
      $extra = [];

      if ($tsUser->is_admod || $display['msgs'] == 2) {
         $extra[] = "<tr><td colspan=\"2\"><p class=\"warning\">".$mysqli->error."</p></td></tr>";
      }
      if (isset($info['file'])) {
         $extra[] = "<tr><td>Archivo</td><td>{$info['file']}</td></tr>";
      }
      if (isset($info['line'])) {
         $extra[] = "<tr class=\"alt\"><td>Línea</td><td>{$info['line']}</td></tr>";
      }
      if (isset($info['query']) && ($tsUser->is_admod || $display['msgs'] == 2)) {
         $extra[] = "<tr><td colspan=\"2\"><kbd>{$info['query']}</kbd></td></tr>";
      }
      $table = '<table border="0"><tbody>' . implode('', $extra) . '</tbody></table>';
   }

   $title = ($type === 'db') ? "Base de datos" : $type;
   exit("<head><meta charset=\"UTF-8\" /><link rel=\"preconnect\" href=\"https://fonts.googleapis.com\"><link href=\"https://fonts.googleapis.com/css2?family=Poppins&display=swap\" rel=\"stylesheet\"><title>Error › {$title}</title><style type=\"text/css\">*,*::after,*::before{padding:0;margin:0;box-sizing: content-box;}html{background:#EEE;}html,body{width:100%;height:100vh;}body{font-family:'Poppins',sans-serif;}#error-page{border:1px solid #CCC;background:#FFF;padding:20px;min-width:650px;max-width:780px;margin:1rem auto}#error-page h1{font-size: 28px;border-bottom: 1px solid #CCC5;padding: 6px;margin-bottom: 10px;}p.warning {background: #FFEEEE;color: #D75454;border:1px solid #D7545455;text-align: center;padding: 10px;margin: 6px 0;}table{border:1px solid #dbe4ef;border-collapse:collapse;text-align:left;width:100%;}table td,table th{padding:5px;}table tbody td{padding:10px;color:#5a5a5a;background:#FDFDFD;border-bottom:1px solid #f3f3f3;font-weight:normal;}table tbody .alt td{background:#E1EEf4;color:#00557F;}table tbody td:first-child{border-left: none;width: 10%;font-weight: bold;border-right: 1px solid #DFDFDF}table tbody tr:last-child td{border-bottom:none;font-weight: normal; }td kbd {line-height:1.325rem;display:block;padding:.875rem;font-size:1rem}</style></head><body><div id=\"error-page\"><h1>{$title}</h1>{$error}{$table}</div></body>");
}

// Borramos la variable por seguridad
unset($db);

/**
 * Función safe_count
 * @author Miguel92 
 * Actua igual que is_countable, excepto que este devuelve 
 * el valor y no un booleano
*/
if (!function_exists('safe_count')) {
   function safe_count($data, $mode = COUNT_NORMAL) {
      return (is_array($data) || $data instanceof Countable) ? count($data, $mode) : 0;
   }
}

if (!function_exists('safe_unserialize')) {
   /**
    * Safely unserialize data.
    *
    * @param string $data The serialized data to be unserialized.
    * @return mixed The unserialized data or an empty array if unserialization fails.
    */
   function safe_unserialize($data) {
      return (!is_null($data) && ($data !== false || $data === 'b:0;')) ? unserialize($data) : [];
   }
}

if(file_exists(TS_ROOT . '.env')) {
	$dotenv = fopen(TS_ROOT . '.env', 'r');
	if ($dotenv) {
	   while (($line = fgets($dotenv)) !== false) {
	      if (preg_match('/\A([a-zA-Z0-9_]+)=(.*)\z/', trim($line), $matches)) {
	         putenv(sprintf('%s=%s', $matches[1], $matches[2]));
	      }
	   }
	   fclose($dotenv);
	}
}
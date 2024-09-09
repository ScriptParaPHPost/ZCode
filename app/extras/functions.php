<?php 

if ( ! defined('TS_HEADER')) 
	exit('No se permite el acceso directo al script');

if(!file_exists(TS_ROOT.'.env') || $_ENV['ZCODE_DB_HOST'] === 'dbhost') header("Location: ./install/");

if ($_ENV['DEBUG_MODE'] === 'true') {
   mysqli_debug("d:t:o," . DIR_ERROR_LOG . "mysqli_error.log");
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
   $mysqli = new mysqli(
   	$_ENV['ZCODE_DB_HOST'], 
   	$_ENV['ZCODE_DB_USER'], 
   	$_ENV['ZCODE_DB_PASS'], 
   	$_ENV['ZCODE_DB_NAME']
   );

	
   // Comprobar el estado de la conexión
   if ($mysqli->connect_errno) {
      throw new Exception("Falló la conexión con MySQL: ({$mysqli->connect_errno}) {$mysqli->connect_error}");
   }
   // Establecer el juego de caracteres utf8
   if (!$mysqli->set_charset( $_ENV['ZCODE_DB_CHARSET'] )) {
      throw new Exception('No se pudo establecer la codificación de caracteres.');
   }
} catch (Exception $e) {
	show_error('No se pudo ejecutar una consulta en la base de datos.', 'db');
}

function withPrefix(string $query = '') {
   // Definir la expresión regular para buscar el contenido entre < y > 
   $expresion_regular = '/\s@([\w_]+)/';
   // Realizar la búsqueda y extraer los resultados
   if (preg_match_all($expresion_regular, $query, $coincidencias)) {
      // $coincidencias[1] contendrá un array con el contenido entre < y > para cada coincidencia
      $resultados = $coincidencias[1];
      // Reemplazar cada coincidencia con $prefix . $resultado
      foreach ($resultados as $resultado) {
         $query = str_replace("@$resultado", $_ENV['ZCODE_DB_PREFIX'] . "$resultado", $query);
      }
      return $query;
   } else return $query;
}
/**
 * Todo lo que se añada a database.php
 * Se ejecutará automáticamente sin 
 * intervención del usuario
*/
if(isset($_GET['migrator']) && $_GET['migrator'] === 'true' || (int)$tsUser->is_admod === 1) {
   include TS_ZCODE . 'migrator.php';
   if(!$success) {
      die('No se pudo migrar correctamente...');
   }
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
		if(!$tsUser->is_admod && $display['msgs'] !== 2) { 
			$info[0] = explode('\\', $info[0]); 
		}
		$info['file'] = ($tsUser->is_admod || $display['msgs'] === 2) ? $info[0] : end($info[0]);
		$info['line'] = $info[1];
		$info['query'] = $data;
	} else {
		$data = $type;
		$type = $info;
		if($type === 'query') { 
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

      if ($tsUser->is_admod || $display['msgs'] === 2) {
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

# Importante para almacenar los logs
if(!is_dir(DIR_ERROR_LOG)) mkdir(DIR_ERROR_LOG, 0777, true);
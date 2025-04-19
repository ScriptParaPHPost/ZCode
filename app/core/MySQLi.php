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

if ( ! defined('ZCODEV3')) exit('No direct script access allowed');

if ($_ENV['DEBUG_MODE'] === 'true') {
   mysqli_debug("d:t:o," . MYSQLI_LOG);
}

$display['msgs'] = $_ENV['MESSAGE_OUTPUT'] ?? 0;

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
   	env('ZCODE_DB_HOST'), 
   	env('ZCODE_DB_USER'), 
   	env('ZCODE_DB_PASS'), 
   	env('ZCODE_DB_NAME')
   );
	
   // Comprobar el estado de la conexión
   if ($mysqli->connect_errno) {
      ShowError("Falló la conexión con MySQL: ({$mysqli->connect_errno}) {$mysqli->connect_error}", 'Conexión');
   }

   // Configurar charset para evitar problemas de codificación
   if (!$mysqli->set_charset( env('ZCODE_DB_CHARSET') )) {
      ShowError('No se pudo establecer la codificación de caracteres.', 'Charset');
   }

} catch (Exception $e) {
	ShowError('No se pudo ejecutar una consulta en la base de datos.', 'db');
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
 * Ejecutar consulta
 */
function db_exec() {
	global $mysqli, $tsUser, $tsAjax, $display;
   //
	$args = func_get_args();
	$info = $args[0] ?? null;
	$type = $args[1] ?? null;
	$data = $args[2] ?? null;
	if(isset($data)) {
		$data = withPrefix($data);
	}
    
   // Si la primera variable contiene un string, se entiende que es la consulta que debe ejecutarse. Esto lo prepara para ello.
   if(is_array($info)) {
		if(!$tsUser->is_admod && (int)$display['msgs'] !== 2) { 
			$info[0] = explode('\\', $info[0]); 
		}
		$info['file'] = ($tsUser->is_admod || (int)$display['msgs'] === 2) ? $info[0] : end($info[0]);
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
    
	return match ($type) {
		'query' => !empty($data) ? (function() use ($mysqli, $data, $info, $tsAjax, $display) {
			try {
				$query = $mysqli->query($data);
				if (!$query) {
					ShowError('No se pudo ejecutar una consulta en la base de datos. ' . $mysqli->error, 'Conectar', $info);
				}
				return $query;
			} catch (Exception $e) {
				if (!$tsAjax && $display['msgs'] && ($info['file'] || $info['line'] || ($info['query'] && $tsUser->is_admod))) {
					ShowError('No se pudo ejecutar una consulta en la base de datos.', 'db', $info);
				}
			}
		})() : null,
		'real_escape_string' => $mysqli->real_escape_string($data),
		'num_rows' => $data->num_rows,
		'fetch_assoc' => $data->fetch_assoc(),
		'fetch_array' => $data->fetch_array(MYSQLI_ASSOC),
		'fetch_row' => $data->fetch_row(),
		'free_result' => $data->free(),
		'insert_id' => $mysqli->insert_id,
		'error' => $mysqli->error,
		default => null,
	};
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

// Función para cerrar la conexión de manera segura
function cerrarConexion($mysqli) {
   if ($mysqli) {
      $mysqli->close();
   }
}

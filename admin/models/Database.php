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

namespace admin\models;

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

class Database {

   private $core;
   private $user;

   public function __construct() {
      $this->core = new tsCore;
      $this->user = new tsUser;
   }

 	/**
    * Convierte una fecha en formato de cadena a un timestamp.
    *
    * @param string $timeformat  La fecha en formato de cadena.
    * @return int                   El timestamp correspondiente a la fecha.
   */
	private function formatedDate(?string $timeformat = ''): int {
      if ($timeformat === null || $timeformat === '') {
         $timeformat = 'now';
      }
      return (new DateTime($timeformat))->getTimestamp();
	}

   /**
    * Ejecuta una acción sobre una tabla de la base de datos.
    *
    * @param string $action  La acción que se desea realizar (ANALYZE, OPTIMIZE, REPAIR, CHECK).
    * @return mixed          El resultado de la ejecución de la consulta.
   */
	private function resultAction(string $action = '') {
	   $tabla = $this->core->setSecure($_POST['table']); 
	   return db_exec([__FILE__, __LINE__], 'query', "$action TABLE $tabla");
	}

  	/**
    * Ejecuta una acción en una o varias tablas de la base de datos y devuelve un mensaje sobre el resultado.
    *
    * @return string         Mensaje que indica el resultado de la operación.
   */
	public function allActions() {
      $action = strtoupper($_POST['action']);
      $tablas = is_array($_POST['tablas']) ? join(',', $_POST['tablas']) : $_POST['tablas'];
      $results = result_array(db_exec([__FILE__, __LINE__], 'query', "$action TABLES $tablas"));
      
      foreach($results as $tabla) {
         if(in_array($tabla["Msg_type"], ['status', 'OK'])) {
            return "1: La operación se completó exitosamente.";
         }
      }
      return "0: No se pudo completar la acción.";
   }

   /**
    * Obtiene información sobre todas las tablas en la base de datos.
    *
    * @return array          Un array de arrays asociativos que contiene la información de cada tabla.
   */
	public function getAllTables() {
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SHOW TABLE STATUS"));
		$tables = [];
		foreach($data as $key => $array) {
			$tabla = [
				'id' => ++$key,
            'name' => $array['Name'],
				'short' => ucfirst(str_replace([$_ENV['ZCODE_DB_PREFIX'], '_'], ['', ' '], $array['Name'])),
				'engine' => $array['Engine'],
				'rows' => (int)$array['Rows'],
				'size' => $this->core->formatBytes($array['Index_length']),
				'cache' => ((int)$array['Data_free'] === 0 ? 0 : $this->core->formatBytes($array['Data_free'])),
				'collation' => $array['Collation'],
				'create' => $this->formatedDate($array['Create_time']),
				'update' => $this->formatedDate($array['Update_time'])
			];
			array_push($tables, $tabla);
		}
		return $tables;
	}

   /**
    * Maneja las acciones comunes (ANALYZE, OPTIMIZE, REPAIR, CHECK) y devuelve un mensaje basado en el resultado.
    *
    * @param string $action  La acción que se desea realizar.
    * @return string         Mensaje que indica el resultado de la operación.
   */
   public function handleAction(string $action = '') {
      $tabla = $_POST['table'];
      if ($result = $this->resultAction($action)) {
         $actionMsg = db_exec('fetch_assoc', $result)['Msg_text'];
         $messages = [
            'Table is already up to date' => "1: La tabla ya está actualizada.",
            'Table is damaged' => "0: La tabla está dañada.",
            'Analyze failed' => "0: La operación de análisis falló.",
            "Table $tabla is already up to date" => "1: La tabla ya está optimizada.",
            "Table $tabla was not optimized because it is too large" => "0: La tabla es demasiado grande para optimizarla.",
            "Table $tabla has been corrupted" => "0: La tabla está dañada.",
            "Table $tabla is not in a valid state for optimization" => "0: La tabla no está en un estado válido para la optimización.",
            "OK" => "1: La tabla está en buen estado.",
            "ERROR 1016 (HY000): Can't open file:" => "0: Error al abrir el archivo de la tabla."
         ];
         return $messages[$actionMsg] ?? "1: La operación $action se completó exitosamente.";
      }
      return "0: Error al ejecutar la operación $action.";
   }

   /**
    * Crea la carpeta de respaldo si no existe y devuelve la ruta de la misma.
    *
    * @return string Ruta de la carpeta de respaldo.
   */
   private function backupFolder() {
   	if(!is_dir(TS_BACKUP)) {
   		mkdir(TS_BACKUP, 0777, true);
   		chmod(TS_BACKUP, 0777);
   	}
   	return TS_BACKUP;
   }

   /**
    * Crea un respaldo de las tablas de la base de datos seleccionadas.
    *
    * @return string Mensaje indicando el éxito de la operación.
   */
   public function createBackup() {
   	$tables = isset($_POST['tablas']) ? $_POST['tablas'] : '*';
    
   	// Obtener todas las tablas de la base de datos
	   if ($tables === '*') {
         $is = 'full';
	      $tables = [];
	      $result = db_exec([__FILE__, __LINE__], 'query', "SHOW TABLES");
	      while ($row = db_exec('fetch_row', $result)) {
	         $tables[] = $row[0];
	      }
	   } else {
         $is = 'partitial';
	      $tables = is_array($tables) ? $tables : explode(',', $tables);
	   }

      $save = date('d.m.Y H:i a');
	   $backupSQL = "/**\n * Copia de seguridad\n * Fecha: $save\n*/\n";
	   // Recorrer las tablas y obtener el SQL de respaldo
   	foreach ($tables as $table) {
   	   $result = db_exec([__FILE__, __LINE__], 'query', "SELECT * FROM $table");
   	   $numFields = $result->field_count;

   	   $backupSQL .= "DROP TABLE IF EXISTS $table;";
   	   $row2 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SHOW CREATE TABLE $table"));
   	   $backupSQL .= "\n" . $row2[1] . ";\n\n";

   	   for ($i = 0; $i < $numFields; $i++) {
   	      while ($row = db_exec('fetch_row', $result)) {
   	         $backupSQL .= "INSERT INTO $table VALUES(";
   	         for ($j = 0; $j < $numFields; $j++) {
   	            $row[$j] = $row[$j] ? addslashes($row[$j]) : '';
   	            $row[$j] = str_replace("\n", "\\n", $row[$j]);
   	            $backupSQL .= '"' . $row[$j] . '"';
   	            if ($j < ($numFields - 1)) {
   	               $backupSQL .= ',';
   	            }
   	         }
   	         $backupSQL .= ");\n";
   	      }
   	   }
   	}
   	
   	// Guardar el archivo SQL en la carpeta especificada
   	$backup_file = 'backup_' . $is . '_' . time() . '.sql';
   	$backup_root = $this->backupFolder() . "/$backup_file";
   	$fileHandle = fopen($backup_root, 'w+');
   	fwrite($fileHandle, $backupSQL);
   	fclose($fileHandle);
   	return "1: Se creo correctamente $backup_file";
   }

   /**
    * Obtiene la lista de archivos de respaldo disponibles.
    *
    * @global object $tsCore Contiene la configuración principal del sistema.
    * @return array Arreglo de archivos de respaldo con sus respectivos detalles.
   */
   public function getBackups() {
   	$folder = $this->backupFolder();
   	$files = scandir($folder);
   	$allFiles = [];
   	foreach($files as $f => $file) {
   		if(in_array($file, ['.', '..'])) continue;
   		$file_route = $folder . $file;
   		$size = $this->core->formatBytes(filesize($file_route));
   		$filename = pathinfo($file, PATHINFO_FILENAME);
   		$allFiles[$f] = [
   			'id' => $f,
   			'name' => $filename,
   			'code_name' => uniqid($this->core->settings['titulo'] . '_' . substr(md5($filename), 0, 8)),
   			'file' => "{$this->core->settings['url']}/storage/backup/$file",
   			'size' => $size,
   			'date' => filectime($file_route)
   		];
   	}
      usort($allFiles, function ($a, $b) {
         return $b['date'] <=> $a['date'];
      });
   	return $allFiles;
   }

   public function delBackup() {
      // Verificar permisos de usuario
      if (!$this->user->is_admod AND $this->user->uid === 1) {
         return '0: No eres el administrador principal, no puedes eliminar.';
      }
       // Validar que el archivo está definido y no está vacío
      if (!isset($_POST['file']) || empty($_POST['file'])) {
         return '0: No existe o no seleccionó el archivo a eliminar.';
      }
      $backup = $this->core->setSecure($_POST['file']);
      $searchFile = $this->backupFolder() . $backup . '.sql';
      // Verificar si el archivo existe
      if (!file_exists($searchFile)) {
         return '0: El archivo no existe.';
      }
      if(!unlink($searchFile)) {
         return '0: No se ha podido eliminar.';
      }
      return '1: Eliminado correctamente';
   }

}
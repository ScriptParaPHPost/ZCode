<?php

class UpdateGithub {

	// Usuario de la cuenta de github
	private $user = 'ScriptParaPHPost';

	// Nombre del repositorio
	private $repo = 'ZCode';

	// main | experimental
	private $branch = 'alpha';

	public $ruta;

	public $status = false;
	
	public function __construct() {
	}


	private function setHeader() {
		$token = $token = (file_exists(TS_ROOT . '.env')) ? getenv('USER_GITHUB_TOKEN') : '';
		$app = "Actualizaciones de los archivos";
		return ['http' => [
			'header' => "Authorization: token {$token}\r\n" .
			"User-Agent: $app\r\n"
		]];
	}

	private function getListFiles() {
		// Obtiene el directorio actual (raíz del script)
		$directorio = $this->ruta; 
		// Generamos un array vacío
	  	$archivosLocales = [];
	   // Recorre el directorio y obtiene la lista de archivos y carpetas
	   foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directorio)) as $archivo) {
	      if ($archivo->isFile() || $archivo->isDir()) {
            $rutaRelativa = str_replace($directorio . DIRECTORY_SEPARATOR, '', $archivo->getPathname());
            $archivosLocales[] = $rutaRelativa;
         }
	   }
	   
	   return $archivosLocales;
	}

	private function deleteDirRecursive(string $directorio = '') {
		$archivos = glob($directorio . '/*');

	  	//$dir = new RecursiveDirectoryIterator($directorio, FilesystemIterator::SKIP_DOTS);
      //$files = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST);
      //foreach($files as $file) {
      //	$file->isDir() ? rmdir($file) : unlink($file);
      //}
	}

	private function delete(array $files = [], $directorioLocal) {
	   foreach($files as $k => $file) {
			$filename = $file['filename'];
			$fileroot = $this->ruta . $filename;
	   	if($file['status'] === 'removed') {
	   		if($k === 0) echo "<hr>Eliminados:<br><br>";
	   		if (is_file($fileroot)) {
	   			unlink($fileroot); // Elimina archivos individuales
					echo " * ./".dirname($filename)."/<strong>".basename($filename)."</strong><br>";
	   		} elseif (is_dir($fileroot)) {
	   			// Llama a la función para eliminar directorios
            	self::deleteDirRecursive($fileroot);
            	// rmdir($fileroot);
					echo " * ./".dirname($filename)."/<strong>".basename($filename)."</strong><br>";
	   		}
	   	}
	   }
	}

	private function downloads(array $files = []) {
		// Crea las carpetas y descarga los archivos modificados
	 	foreach ($files as $k => $archivo) {
	 		if($k === 0) echo "Actualizados:<br><br>";
			$filename = $archivo['filename'];
			$ruta_archivo = $this->ruta . $filename;
			// Evita que descargue los archivos eliminados
			if($archivo['status'] === 'removed') continue;
			$contenido = file_get_contents($archivo['raw_url']);
			//
			if ($contenido !== false) {
				// Crea la carpeta si no existe
				$directorio = dirname($ruta_archivo);
				if (!is_dir($directorio)) mkdir($directorio, 0777, true);

				// Asignamos los permisos antes
				$permisos_originales = is_dir($directorio) ? 0777 : 0666;
				$permisosd = is_dir($directorio) ? 0755 : 0644;

				// Guarda los permisos originales antes de cambiarlos
				$permisos_actuales = fileperms($directorio);
				chmod($directorio, $permisos_originales);

				// Guarda el archivo en la carpeta
				copy($archivo['raw_url'], $ruta_archivo);
				echo " * ./".dirname($filename)."/<strong>".basename($filename)."</strong><br>";
				$this->status = true;

				// Restauramos los permisos
				chmod($directorio, $permisos_actuales);
		  	} else echo "Error al descargar: {$filename}<br>";
	 	}
	}

	public function update() {
		$url = "https://api.github.com/repos/{$this->user}/{$this->repo}/commits/{$this->branch}";
		// HEADER
		$context = stream_context_create(self::setHeader());
		// Realiza la solicitud a la API de GitHub
		$datos = file_get_contents($url, false, $context);
		if ($datos === false) echo "No se pudo obtener la información de GitHub.";
		// Obtenemos los commits
		$commit = json_decode($datos, true);
		if (!$commit || !isset($commit['files'])) {
			echo "No se encontraron archivos modificados en el último commit.";
	 	}

    	// Descargamos los archivos
    	self::downloads($commit['files']);
	 	// Quitamos los archivos/carpetas que ya no estan
	 	self::delete($commit['files'], $this->ruta);
	}

	public function updateTable(bool $action = true) {
		global $tsCore;
		// Esta actualizado?
		$status = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT updated FROM @configuracion WHERE tscript_id = 1"));
		if((int)$status['updated'] === 0 OR $action === false) {
			$change = $action ? 1 : 0;
			return (db_exec([__FILE__, __LINE__], 'query', "UPDATE @configuracion SET updated = $change WHERE tscript_id = 1")) ? 'La tabla sea actualizado correctamente' : 'No se pudo actualizar';
		} else return 'Ya esta actualizado';
	}

	public function establecerPermisos(bool $act = true, string $ruta = '', int $permisos = 0777) {
	   // Ruta de la carpeta principal
	   $directorio = empty($ruta) ? $this->ruta : $ruta;
	   if(is_dir($directorio)):
	   	// Cambiar permisos de la carpeta
	      chmod($directorio, $permisos);
	       // Recorrer archivos y carpetas dentro de esta carpeta
	      $archivos = scandir($directorio);
	      foreach ($archivos as $archivo):
	      	// Pasamos por alto esto
	      	$no = ['.', '..', '.git', '.gitignore', '.gitattributes', '.lock', 'README.md', 'cache', 'smarty', 'avatar', 'uploads'];
		      if (in_array($archivo, $no)) continue;
		      //
		      $filename = rtrim($directorio, '\\') . '/' . $archivo;
		      if($act) $permiso = is_dir($filename) ? 0777 : 0666;
	         else $permiso = is_dir($filename) ? 0755 : 0644;
	         if (is_dir($filename)) $this->establecerPermisos($filename, $permiso);
	   		//echo (is_dir($filename) ? 'Carpeta: ' : 'Archivo: ') . $filename . '<br>';
	      endforeach;
	   endif;
	}

}
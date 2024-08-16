<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Clase para el manejo de las actualizaciones
 *
 * @name    c.actualizacion.php
 * @author  Miguel92
 */

class tsActualizacion {

	protected $EXCLUDE = [
		'.git',
		'assets',
		'assets/images/avatar',
		'assets/images/favicon',
		'assets/images/portadas',
		'cache',
		'install',
		'update',
		'.env',
		'.lock',
		'config.inc.php'
	];

	/**
	 * Acceso limitado a lectura
	*/
	protected $GITHUB_USER_TOKEN = "zJBE2HbWnZY3OITjiQIVzln7HCySCM389WK1";

	protected $GITHUB_USER_AGENT = "Updates for files";

	protected $GITHUB_VARIABLE = "USER_GITHUB_TOKEN";

	protected $USER = "ScriptParaPHPost";

	protected $REPO = "ZCode";

	protected $commitSha, $modifiedFiles;

	//commits

	private function envFile() {
		return TS_ROOT . '.env';
	}

	public function getFileENV() {
		if(file_exists($this->envFile())) {
			$this->GITHUB_USER_TOKEN = getenv($this->GITHUB_VARIABLE);
			return true;
		} else {
			return false;
		}
	}

	public function createToken() {
		global $tsCore;
		$env = $this->envFile();
		if(!file_exists($this->envFile())) {
			copy(TS_ROOT . '.env.example', $env);
			$get = file_get_contents($env);
			$add = explode("\n", $get);
			$add[0] = "{$this->GITHUB_VARIABLE}=" . $this->GITHUB_USER_TOKEN;
			file_put_contents($env, implode("\n", $add));
			return true;
		}
		return false;
	}

	private function setHeader() {
		return ['http' => [
			'header' => "Authorization: token {$this->GITHUB_USER_TOKEN}\r\n" .
			"User-Agent: {$this->GITHUB_USER_AGENT}\r\n"
		]];
	}

	private function api_response(string $type = '', string $commit = '') {
		$user_repo = "{$this->USER}/{$this->REPO}";
		$curl['api'] = "https://api.github.com/repos/$user_repo/commits";
		$curl['raw'] = "https://raw.githubusercontent.com/$user_repo/{$this->commitSha}/";

		if(!empty($commit)) $curl[$type] .= "/$commit" . ($type === 'api' ? '' : "/");
		// Configuración de la solicitud CURL
		$ch = curl_init($curl[$type]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); // GitHub requiere un User-Agent válido
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->setHeader());

		$response = curl_exec($ch);
		curl_close($ch);
		return json_decode($response);
	}

	public function saveIDUpdate(string $type = '', string $sha_short = '') {
		if($type === 'save') {
			db_exec([__FILE__, __LINE__], 'query', "UPDATE @configuracion SET update_id = '$sha_short' WHERE tscript_id = 1");
		} elseif($type === 'get') {
			$sha_short = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT update_id FROM @configuracion WHERE tscript_id = 1"))['update_id'];
		}
		return $sha_short;
	}

	public function getLastCommit() {
		$response = $this->api_response('api');
		if(isset($_SESSION["sha"]) && $response[0]->sha === $_SESSION['sha']) {
			return $_SESSION['sha'];
		} else {
			$_SESSION['sha'] = $response[0]->sha;
			$this->saveIDUpdate('save', substr($_SESSION['sha'], 0, 20));
			return $_SESSION['sha'];
		}
	}

	public function getLastCommitFiles() {
		if(!isset($_SESSION["commit"])) {
			$api = $this->api_response('api', $_SESSION['sha']);
			$_SESSION["commit"] = [
				'author' => [
					'name' => $api->commit->author->name,
					'date' => strtotime($api->commit->author->date),
				],
				'stats' => [
					'total' => $api->stats->total,
					'additions' => $api->stats->additions,
					'deletions' => $api->stats->deletions
				]
			];
			$_SESSION["files"] = $api->files;
		}		
		return $_SESSION['commit'];
	}

	public function filesStatus() {
		return $_SESSION["files"];
	}

	private function cambiarPermisos() {
	   $permisosOriginales = [];

	   $iterator = new RecursiveIteratorIterator(
      	new RecursiveDirectoryIterator(TS_ROOT, RecursiveDirectoryIterator::SKIP_DOTS),
        	RecursiveIteratorIterator::SELF_FIRST
    	);

	   foreach ($iterator as $item) {
	      $path = $item->getPathname();
	      foreach ($this->EXCLUDE as $ex) {
	         if (stripos($path, $ex) !== false) {
	            continue 2;
	         }
	      }
	      $permisosOriginales[$path] = substr(sprintf('%o', $item->getPerms()), -4);
	      $permiso = $item->isDir() ? 0777 : 0666;
	      chmod($path, $permiso);
	   }
	   return $permisosOriginales;
	}

	private function restaurarPermisos($permisos) {
	   foreach ($permisos as $path => $permiso) {
	      chmod($path, octdec($permiso));
	   }
	}

	public function getFilesUpdate() {
		$files = [];
		$msg = '';
		$permisos = [
			'original' => ['file' => 0644, 'dir' => 0755],
			'cambiar' => ['file' => 0666, 'dir' => 0777],
		];
		
		$permisos = $this->cambiarPermisos();
		foreach($this->filesStatus() as $k => $file) {
			$filename = $file->filename;
			$root_filename = TS_ROOT . $filename;
			# Empezando la descarga de los archivos
			$contenido = file_get_contents($file->raw_url);
			if ($contenido !== false) {
				// Crea la carpeta si no existe
				$directorio = dirname($root_filename);
				if (!is_dir($directorio)) mkdir($directorio, 0777, true);
				// Guarda el archivo en la carpeta
				if(copy($file->raw_url, $root_filename)) {
					$copy[$k] = 1;
				} else $copy[$k] = 0;
			}
		}
		$this->restaurarPermisos($permisos);
		return in_array(1, $copy);
	}
}
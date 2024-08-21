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

	protected $GITHUB_USER_AGENT = "Updates for files";

	protected $GITHUB_VARIABLE = "USER_GITHUB_TOKEN";

	protected $USER = "ScriptParaPHPost";

	protected $REPO = "ZCode";

	protected $commitSha, $modifiedFiles;

	public $BRANCH;

	public function api_response(string $type = '', string $commit = '') {
		$user_repo = "{$this->USER}/{$this->REPO}";
		$repos = "https://api.github.com/repos";
		$curl[''] = "$repos/$user_repo";
		$curl['api'] = "$repos/$user_repo/commits";
		$curl['info'] = "$repos/$user_repo/branches/{$this->BRANCH}";
		$curl['raw'] = "https://raw.githubusercontent.com/$user_repo/{$this->commitSha}/";

		if(!empty($commit)) $curl[$type] .= "/$commit" . ($type === 'api' ? '' : "/");
		// Configuración de la solicitud CURL
		$ch = curl_init($curl[$type]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); // GitHub requiere un User-Agent válido
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'http' => [
				'header' => "User-Agent: {$this->GITHUB_USER_AGENT}"
			]
		]);

		$response = curl_exec($ch);
		curl_close($ch);
		return json_decode($response);
	}

	public function getLastCommits() {
		$datos = [];
		foreach($this->api_response('api') as $cid => $otherCommit) {
			$datos[$cid]['sha'] = $otherCommit->sha;
			$datos[$cid]['date'] = strtotime($otherCommit->commit->author->date);
		}
		return $datos;
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
		if(is_array($response)) {
			if(isset($_SESSION["sha"]) && $response[0]->sha === $_SESSION['sha']) {
				return $_SESSION['sha'];
			} else {
				$unset = ['sha', 'commit', 'files'];
    			foreach($unset as $del) unset($_SESSION[$del]);
				$_SESSION['sha'] = $response[0]->sha;
				$this->saveIDUpdate('save', substr($_SESSION['sha'], 0, 20));
				return $_SESSION['sha'];
			}
		}
	}

	public function getLastCommitFiles() {
	   if (!isset($_SESSION["commit"]) || (isset($_GET['sha']) && $_GET['sha'] !== $_SESSION['sha'])) {
	      $shaToFetch = isset($_GET['sha']) ? $_GET['sha'] : $_SESSION['sha'];
	      $api = $this->api_response('api', $shaToFetch);
	      $tsCommit = [
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
	      // Si estamos utilizando $_GET, no actualizamos $_SESSION
	      if (!isset($_GET['sha'])) {
	         $_SESSION["commit"] = $tsCommit;
	         $_SESSION["files"] = $api->files;
	      }
	      // Devolvemos la información del commit solicitado (o el último por defecto)
	      return [
	         'commit' => $tsCommit,
	         'files' => $api->files
	      ];
	   }
	   // Si no hay $_GET['sha'], devolvemos lo almacenado en $_SESSION
	   return [
	      'commit' => $_SESSION['commit'],
	      'files' => $_SESSION['files']
	   ];
	}

	public function filesStatus() {
		return $this->getLastCommitFiles()["files"];
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
		
		$permisos = $this->cambiarPermisos();
		foreach($this->filesStatus() as $k => $file) {
			$filename = $file->filename;
			$root_filename = TS_ROOT . $filename;
			# Empezando la descarga de los archivos
			$contenido = file_get_contents($file->raw_url);
			if($file->status === 'removed') {
				unlink($root_filename);
			}
			if ($contenido !== false AND $file->status !== 'removed') {
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

	public function getUser() {
		$data = $this->api_response();
		$info['name'] = $data->name;
		$info['avatar'] = $data->owner->avatar_url;
		$info['description'] = $data->description;
		$info['tags'] = $data->topics;
		return $info;
	}
}
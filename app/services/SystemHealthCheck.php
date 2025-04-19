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

class SystemHealthCheck {

	private $status = [];

	private $startTime;
	private $storagePath;
	private $jsonFile;

	public function __construct() {
		$this->startTime = microtime(true);
    	$this->storagePath = dirname(__DIR__, 2) . '/storage';
    	$this->jsonFile = $this->storagePath . '/system_health.json';
	}

	private function getSizeFormatted(int $size) {
	   $units = ['B', 'KB', 'MB', 'GB', 'TB'];
	   $i = 0;
	   while ($size >= 1024 && $i < count($units) - 1) {
	      $size /= 1024;
	      $i++;
	   }
	   return round($size, 2) . ' ' . $units[$i];
	}

	private function sendToDiscord($url, $payload) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
	}

	private function readJsonFile($path) {
	   return file_exists($path) ? json_decode(file_get_contents($path), true) : [];
	}

	//
	public function getExecutionTime(): string {
    	return round((microtime(true) - $this->startTime) * 1000, 2) . ' ms';
	}

	public function getStatus() {
		return $this->status;
	}

	public function serveHealthCheck() {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($this->getStatus(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	public function logStatus() {
		$log = time() . ' | Status: ' . json_encode($this->status) . PHP_EOL;
		file_put_contents(dirname(__DIR__, 2) . '/storage/system_health.log', $log, FILE_APPEND);
	}

	public function run(bool $log = false) {
		$this->checkDatabase();
		$this->checkCache();
		$this->checkComposerPackages();
		$this->saveJsonEstadoFormateado();
		if($log) $this->logStatus();
	}

	public function checkDatabase() {
		$dbStartTime = microtime(true);
		$dbname = env('ZCODE_DB_NAME');
	   $size = 0;
	   // Activamos estilo de excepciones para mysqli
	   mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ALL);

	   try {
	      $mysqli = @new mysqli(env('ZCODE_DB_HOST'),env('ZCODE_DB_USER'),env('ZCODE_DB_PASS'), $dbname);

			if ($mysqli->connect_error) {
				throw new \Exception("Fallo de conexión: " . $mysqli->connect_error);
			}
			// Tiempo de respuesta
			$latency = microtime(true) - $dbStartTime;
			// Peso de la base de datos
			$result = $mysqli->query("SELECT SUM(data_length + index_length) AS size FROM information_schema.tables WHERE table_schema = '{$dbname}'");

	    	if ($result && $row = $result->fetch_assoc()) {
	        	$size = (int) $row['size'];
	    	}
			// Guardamos los estados
	      $this->status['database']['status'] = 'operativo';
	      $this->status['database']['size'] = $this->getSizeFormatted($size);
	      $this->status['database']['latency'] = $latency < 1 ? round($latency, 2) . ' ms' : 'no operativo: Latencia alta (' . round($latency, 3) . 's)';

	      $mysqli->close();
	   } catch (\mysqli_sql_exception $e) {
	     	$this->status['database']['status'] = 'no operativo: ' . $e->getMessage();
	      $this->status['database']['size'] = '0 bytes';
	      $this->status['database']['latency'] = '0 ms';
	   }
	}

	public function checkCache() {
		$folderPath = dirname(__DIR__, 2) . '/storage/cache';
	   $size = 0;

	  	if (!is_dir($folderPath)) {
	  		$this->status['cache']['status'] = 'no operativo: no existe carpeta';
	      return ;
	   }

	   foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath, FilesystemIterator::SKIP_DOTS)) as $file) {
	      $size += $file->getSize();
	   }
		
		$this->status['cache']['status'] = 'operativo';
		$this->status['cache']['size'] = $this->getSizeFormatted($size);
	}

	public function sendAlert($subject, $message) {
		// Enviar alerta por correo electrónico
		# mail('joelmiguelvalente@gmail.com', $subject, $message);

		$webhook_id = "1362487243243126977";
		$webhook_token = "c_V072iJp-x_CHads4qBX75DRTZi7gfHLtZW28l_DdHSpQ6lanYtGRCjfbvfvib6TE2g";

		// Enviar alerta a Discord (usando webhook)
		$discordWebhookUrl = "https://discord.com/api/webhooks/$webhook_id/$webhook_token";
		$embed = [
   	   'title' => '🚨 Alerta del Sistema',
   	   'description' => "**$subject**\n\n$message",
   	   'color' => hexdec('FF0000'), // rojo fuerte
   	   'timestamp' => date('c'), // formato ISO8601
   	   'footer' => [
   	      'text' => 'ZCode Monitoring',
   	      'icon_url' => 'https://zcodev.alwaysdata.net/assets/images/favicon/logo-256.webp' // opcional
   	   ]
   	];
	
   	$payload = json_encode([
   	   'username' => 'ZCode Bot',
   	   'avatar_url' => 'https://zcodev.alwaysdata.net/assets/images/favicon/logo-32.webp',
   	   'embeds' => [$embed]
   	]);
	
		$this->sendToDiscord($discordWebhookUrl, $payload);
	}

	private function verificar_componentes($anterior, &$data, $timestamp) {
		$huboCambio = false;
	   // Componentes clave a verificar
	   $componentes = ['database', 'cache'];

	   foreach ($componentes as $clave) {
	      $estadoAnterior = $anterior[$clave] ?? null;
	      $estadoNuevo = $data[$clave];

	      if ($estadoAnterior !== $estadoNuevo && strpos($estadoNuevo['status'], 'operativo') === false) {
	         $data['incidentes'][] = [
	            'date' => $timestamp,
	            'component' => $clave,
	            'details' => $estadoNuevo
	         ];
	         $huboCambio = true;
	      }
	   }
	   return $huboCambio;
	}

	private function verificar_estado_composer($anterior, &$data, $timestamp) {
	   $huboCambio = false;
	   $estadoComposerAnterior = $anterior ?? null;
	   $estadoComposerNuevo = $data['composer']['estado'] ?? null;

	   if ($estadoComposerAnterior !== $estadoComposerNuevo && $estadoComposerNuevo !== 'operativo') {
	      $data['incidentes'][] = [
	        	'date' => $timestamp,
	        	'component' => 'composer',
	        	'details' => $estadoComposerNuevo
	      ];
	      $huboCambio = true;
	   }
	   return $huboCambio;
	}

	private function verificar_estado_paquetes($anterior, &$data, $timestamp) {
	   $huboCambio = false;

	   // Asegurar que el array existe
	   $paquetesNuevo = $data['composer']['packages'] ?? [];

	   // Evitar errores si tampoco hay datos anteriores
	   $paquetesAnterior = $anterior['composer']['packages'] ?? [];

	   if (!is_array($paquetesNuevo)) {
	       return false; // Nada que verificar
	   }

	   foreach ($paquetesNuevo as $pkg => $estado) {
	      if (!isset($paquetesAnterior[$pkg]) || $paquetesAnterior[$pkg] !== $estado) {
	         if ($estado !== 'instalado') {
	            $data['incidentes'][] = [
	               'date' => $timestamp,
	               'component' => 'composer (paquete)',
	               'details' => "$pkg: $estado"
	            ];
	            $huboCambio = true;
	         }
	      }
	   }
	   return $huboCambio;
	}


	public function saveJsonEstadoFormateado() {
	   $estadoActual = $this->getStatus();
	   $timestamp = time();

	   $data = array_merge([
	   	'generado' => $timestamp,
	   	'incidentes' => [],
	   ], $estadoActual, ['tiempo_ejecucion' => $this->getExecutionTime()]);
		
		$huboCambio = false;

		if (file_exists($this->jsonFile)) {
	      $anterior = json_decode(file_get_contents($this->jsonFile), true);

	      // Copiar incidentes anteriores
	      if (isset($anterior['incidentes']) && is_array($anterior['incidentes'])) {
	         $data['incidentes'] = $anterior['incidentes'];
	      }
	      $huboCambio = $this->verificar_componentes($anterior, $data, $timestamp);
	      // Verificar cambios en Composer
	      $huboCambio = $this->verificar_estado_composer($anterior['composer']['status'], $data, $timestamp);
	      // Verificar cambios en paquetes Composer
	      $huboCambio = $this->verificar_estado_paquetes($anterior['composer']['packages'], $data, $timestamp);
	      // Si no hubo ningún cambio, no se guarda
	      if (!$huboCambio) {
	         return;
	      }
	   }

	   // Guardar solo si hubo cambio o si no existía antes
	   file_put_contents($this->jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
	}

	private function getComposerPackages() {
		$composerFile = dirname(__DIR__, 2) . '/composer.json';
		$composerDecode = $this->readJsonFile($composerFile);
		$packageInstalled = [];
		foreach($composerDecode['require'] as $package => $version) {
		   $packageInstalled[] = $package;
		}
		return $packageInstalled;
	}

	public function checkComposerPackages() {
	   $jsonPath = $this->storagePath . '/../vendor/composer/installed.json';

	   $installedData = $this->readJsonFile($jsonPath);

	   if (!$installedData) {
	      $this->status['composer'] = [
	         'status' => 'no operativo',
	         'message' => 'installed.json no válido o no encontrado',
	         'packages' => [],
	         'total' => 0
	      ];
	      return;
	   }

	   $packages = $installedData['packages'] ?? $installedData;
	   $installedPackages = array_column($packages, 'name');
	   $requiredPackages = $this->getComposerPackages();

	   $packageStates = array_map(function ($pkg) use ($installedPackages) {
	      return in_array($pkg, $installedPackages) ? 'instalado' : 'faltante';
	   }, $requiredPackages);

	   $this->status['composer'] = [
	      'status' => in_array('faltante', $packageStates) ? 'no operativo' : 'operativo',
	      'packages' => array_combine($requiredPackages, $packageStates),
	      'total' => count($requiredPackages)
	   ];
	}

	public function verificar(): array {
	   $this->status = [];
	   // Tiempos
	   $startTime = microtime(true);
	   $timestamp = time();
	   // Leer archivo si existe
	   $data = file_exists($this->jsonFile) ? json_decode(file_get_contents($this->jsonFile), true) : [];
	   // Mantener "generado" o establecer si no existe
	   $data['generado'] = $data['generado'] ?? $timestamp;
	   // Agregar "verificado" actual
	   $data['verificado'] = $timestamp;
	   // Ejecutar verificaciones
		$this->checkDatabase();
		$this->checkCache();
		$this->checkComposerPackages();
	   // Actualizar datos
	   $data['incidentes'] = [];
	   $data['database'] = $this->status['database'] ?? [];
	   $data['cache'] = $this->status['cache'] ?? [];
	   $data['composer'] = $this->status['composer'] ?? [];
	   $data['tiempo_ejecucion'] = $this->getExecutionTime($startTime);
	   // Guardar el archivo
	   file_put_contents($this->jsonFile, json_encode($data, JSON_PRETTY_PRINT));
	   return $data;
	}

}
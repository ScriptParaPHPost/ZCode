<?php

/**
 * Cacheando la base de datos
 * Copyright 2024 Miguel92, Todos los derechos reservados
 * @author  	Miguel92
 * @version 	v1.0
 */

/**
 * @name SqlCached.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v1.0.0
**/

if (!defined('TS_HEADER')) die('mmm...que estarás haciendo!');

class sqlCache {

	protected $storage = 'cacheSQL';

	protected $prefix = 'sqlcache_';

	protected $extension = '.cache';

	// El tiempo de actualización de la caché en segundos.
	protected $upgrade = 300; # 5 Min.

	public function __construct() {
		$this->storage = $this->setStorage();
	}

	private function setStorage() {
		$storage = TS_STORAGE . $this->storage . TS_PATH;
		if(!is_dir($storage)) {
			mkdir($storage, 0777, true);
		}
		return $storage;
	}

	private function getTimeSqlCached(string $file = '') {
      // Verificar si el archivo existe antes de intentar obtener su tiempo de modificación
      if (file_exists($file)) {
         return (time() - filemtime($file) < $this->upgrade);
      }
      return false;
   }

	public function verifySqlCached(string $cache_key = '') {
		return file_exists($this->storage . $this->prefix . $cache_key . $this->extension);
	}

	public function sqlCached(string $cache_key = '', array $data = []) {
      $filename = $this->storage . $this->prefix . $cache_key . $this->extension;

      // Comparar el contenido actual de la base de datos con el caché
      $isCacheValid = $this->verifySqlCached($cache_key) && $this->getTimeSqlCached($filename);
      $currentData = $isCacheValid ? unserialize(file_get_contents($filename)) : null;

      if (!$isCacheValid || $currentData !== $data) {
         // Guardar los nuevos datos en caché si están obsoletos o diferentes
         file_put_contents($filename, serialize($data));
      } else {
         $data = $currentData; // Mantener los datos actuales si el caché es válido
      }

      return $data;
   }

	public function getSqlCached(string $namekey = '') {;
      $filename = $this->storage . $this->prefix . $namekey . $this->extension;
      if ($this->verifySqlCached($namekey) && !$this->getTimeSqlCached($filename)) {
         return unserialize(file_get_contents($filename));
      } else return null;
   }

   public function setCache(string $key = '', array $data = [], int $max = 0, int $page = 0) {
   	$key .= ($max !== $page OR $max <= 0) ? '' : "_p{$page}";
   	if(!$this->verifySqlCached($key)) {
   		return $this->sqlCached($key, $data);
   	} else {
   		return $this->getSqlCached($key);
   	}
   }


	/**
	 * Obtiene los datos almacenados en caché o los genera y almacena en caché si no están disponibles o son obsoletos.
	 *
	 * @param string $cacheKey La clave de la caché utilizada para identificar los datos.
	 * @param int $upgrade El tiempo de expiración de la caché en segundos. Por defecto 3600 (1 hora).
	 * @return mixed Los datos recuperados de la caché o generados si no están disponibles o son obsoletos.
	*/
	public function getCachedData($cacheKey) {
		// Existe el archivo
		if ($this->upgrade && file_exists($cacheFile) && (time() - filemtime($cacheFile) < $upgrade)) {
			return unserialize(file_get_contents($cacheFile));
		} else {
			unlink($cacheFile);
	      $data = $this->$cacheKey(); # Obtiene el resultado de la funcion
	      file_put_contents($cacheFile, serialize($data));
	      return $data;
	   }
	}

}
$sqlCache = new sqlCache;
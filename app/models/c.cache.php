<?php 

if ( ! defined('ZCODE2')) exit('No se permite el acceso directo al script');

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

class tsCache {

   private $cacheDir = 'sentencias';

   private $cacheTTL = 150;

   public function __construct($cacheDir = null, $cacheTTL = null) {
      $dir = TS_STORAGE . $this->cacheDir . DIRECTORY_SEPARATOR;
      if (!is_dir($dir)) {
         mkdir($dir, 0777, true);
      }
      $this->cacheDir = rtrim($dir, '/') . '/';
      if ($cacheTTL) {
         $this->cacheTTL = $cacheTTL;
      }
   }

   public function generate($cacheKey, callable $callback, $changeDetector = null) {
      $cacheFile = $this->cacheDir . $cacheKey . '.json';
      // Verificar si el cache existe y es válido
      if (file_exists($cacheFile)) {
         $cacheData = json_decode(file_get_contents($cacheFile), true);

         // Validar el indicador de cambios si está disponible
         if ($changeDetector && is_callable($changeDetector)) {
            $currentState = call_user_func($changeDetector);
            if ($cacheData['state'] === $currentState) {
               return $cacheData['data']; // Retornar datos del cache
            }
         } else {
           	// Validar por tiempo (TTL)
           	if ((time() - $cacheData['generated']) < $this->cacheTTL) {
           	   return $cacheData['data'];
           	}
         }
      }
      // Generar nuevos datos
      $data = call_user_func($callback);
      $state = $changeDetector ? call_user_func($changeDetector) : null;
      // Guardar en el cache
      $cacheData = [
         'generated' => time(),
         'state' => $state,
         'data' => $data
      ];
      file_put_contents($cacheFile, json_encode($cacheData));
      return $data;
   }
}
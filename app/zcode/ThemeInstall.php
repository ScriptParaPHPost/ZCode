<?php

/**
 * Instalacion Automatica de theme v4
 * Copyright 2024 Miguel92, Todos los derechos reservados
 * @author  	Miguel92
 * @version 	v4.0
 */

/**
 * @name ThemeInstall.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v2.0.0
 * @description Definiremos las constantes de forma global
**/

if (!defined('TS_HEADER')) die('mmm...que estarás haciendo!');

class ThemeInstaller {

   private $themesDir;

   private $tsCore;

   private $style = 'theme';

   public function __construct($themesDir, $tsCore) {
      $this->themesDir = $themesDir;
      $this->tsCore = $tsCore;
   }

	/**
	 * Extrae información de un archivo CSS.
	 *
	 * @param string $filePath La ruta al archivo CSS.
	 * @return array|null Un array con la información extraída del archivo CSS, o null si no se encontró la información.
	*/
   public function extractCssInfo($filePath) {
      $content = file_get_contents($filePath);
      $pattern = "/\*\*\s*\n" . 
      "\s*\*\s*@name:\s*(.*?)\s*\n" . 
      "\s*\*\s*@folder:\s*(.*?)\s*\n" . 
      "\s*\*\s*@copyright:\s*(.*?)\s*\n" . 
      "\s*\*\s*@link:\s*(.*?)\s*\n" . 
      "\s*\*\s*@description:\s*(.*?)\s*\n" . 
      "\s*\*\s*@tags:\s*(.*?)\s*\n" . 
      "\s*\*\s*@other:\s*(.*?)\s*\n" . 
      "\s*\*\*/";
      if (preg_match($pattern, $content, $matches)) {
         return [
            'name' => trim($matches[1]),
            'folder' => trim($matches[2]),
            'copyright' => trim($matches[3]),
            'link' => trim($matches[4]),
            'description' => trim($matches[5]),
            'tags' => trim($matches[6]),
            'other' => trim($matches[7])
         ];
      }
      return null;
   }

   private function getFileCss(string $theme = '') {
   	$theme_dir = $this->themesDir . $theme . TS_PATH;
   	$theme_css = $theme_dir . $this->style . '.css';
   	return $theme_css;
   }

	/**
	 * Procesa un tema específico y lo añade a la base de datos si no existe.
	 *
	 * @param string $theme El nombre del tema a procesar.
	 * @return void
	*/
   private function processTheme($theme) {
      $themeCssFile = $this->getFileCss($theme);
      if (is_file($themeCssFile)) {
         $getInfo = $this->extractCssInfo($themeCssFile);
         if ($getInfo) {
            $query = "INSERT INTO @temas (t_name, t_url, t_path, t_copy) SELECT '{$getInfo['name']}', '{$this->tsCore->settings['url']}/themes/$theme', '{$getInfo['folder']}', '{$getInfo['copyright']}' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM @temas WHERE t_path = '{$getInfo['folder']}')";
            db_exec([__FILE__, __LINE__], 'query', $query);
         }
      }
   }

	/**
	 * Obtiene una lista de los temas en el directorio de temas, filtrando aquellos con guiones o símbolos especiales.
	 *
	 * @param bool $action Determina si la acción es para incluir o excluir ciertos temas.
	 * @return array Un array con los nombres de los temas filtrados.
	*/
   private function diffThemes(bool $action = true) {
   	$notEntry = ['.', '..', 'default'];
   	if(!$action) $notEntry = array_slice($notEntry, 0, -1);
   	$themes = array_diff(scandir($this->themesDir), $notEntry);
   	// Filtrar las carpetas que contengan guiones o símbolos especiales
		$filteredThemes = array_filter($themes, function($theme) {
		   return preg_match('/^[\w\s]+$/', $theme);
		});
		return $filteredThemes;
   }

   /**
	 * Installa temas en el sistema.
	 *
	 * @return void
	*/
   public function installThemes() {
      $themes = $this->diffThemes();
      foreach ($themes as $theme) {
         $this->processTheme($theme);
      }
   }

	/**
	 * Verifica si los temas instalados están presentes en el directorio de temas.
	 *
	 * @return void
	*/
   public function verifyThemesInstalled() {
	   // Obtener los temas instalados en la base de datos
	   $installedThemes = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT tid, t_name, t_path FROM @temas"));
	   // Obtener las carpetas de temas actualmente en el directorio (excluyendo los predeterminados)
	   $currentThemes = $this->diffThemes(false); 
	   // Recorrer los temas instalados y verificar si existen en el directorio
	   foreach ($installedThemes as $theme) {
	      if (!in_array($theme['t_path'], $currentThemes)) {
	         // Si la carpeta no existe, eliminar el registro de la base de datos
	         db_exec([__FILE__, __LINE__], 'query', "DELETE FROM @temas WHERE tid = {$theme['tid']}");
	      }
	   }
	}

	private function markdownToHtml($markdown) {
	   $markdown = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $markdown);
	   $markdown = preg_replace('/__(.*?)__/', '<strong>$1</strong>', $markdown);
	   $markdown = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $markdown);
	   $markdown = preg_replace('/_(.*?)_/', '<em>$1</em>', $markdown);
	   $markdown = preg_replace('/_(.*?)_/', '<u>$1</u>', $markdown);
	   $markdown = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="$2">$1</a>', $markdown);
	   $markdown = preg_replace('/!\[(.*?)\]\((.*?)\)/', '<img src="$2" alt="$1">', $markdown);
	   return $markdown;
	}


	public function getThemeInfo(array &$data = [], int $tid = 0, array $theme = []) {
		$theme_info = $this->extractCssInfo("{$theme['t_url']}/{$this->style}.css");
		$data[$tid]['t_name'] = $this->tsCore->setSecure($theme_info['name']) ?? $theme['t_name'];
		$data[$tid]['t_link'] = $this->tsCore->setSecure($theme_info['link']) ?? '';
		$data[$tid]['t_copyright'] = $this->tsCore->setSecure($theme_info['copyright']) ?? $theme['t_copy'];
		$data[$tid]['t_description'] = $this->markdownToHtml($this->tsCore->setSecure($theme_info['description']));
		$data[$tid]['t_tags'] = preg_split('/\s*,\s*/', $this->tsCore->setSecure($theme_info['tags']));
		$data[$tid]['t_other'] = $this->tsCore->setSecure($theme_info['other']) ?? '';
		return $data;
	}

}

// Ejemplo de uso
$installer = new ThemeInstaller(TS_THEMES, $tsCore);
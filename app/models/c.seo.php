<?php

if (!defined('ZCODE2')) exit('No se permite el acceso directo al script');

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

class tsSeo {

	public $robots;
	
	public $seo;

	public function __construct() {
		$this->robots = TS_ROOT . 'robots.txt';
		$this->seo = $this->getSeo();
	}

	# ===================================================
	# SEO
	# * getSEO() :: Obtenemos toda la informacion
	# * addRobotsTXT() :: Generamos el robots.txt
	# ===================================================
	public function getSeo() {
		$sql = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT seo_id, seo_titulo, seo_descripcion, seo_portada, seo_keywords, seo_robots, seo_sitemap, seo_google_verification, seo_google_verification_active, seo_google_analytics FROM @seo WHERE seo_id = 1'));
		if($sql === null) return [];
		return $sql;
	}

	public function addRobotsTXT() {
		global $tsCore;
		$robots = "User-agent: *\n";
		$disallow = [
			'admin/', 'app/', 'assets/', 'auth/', 'config/', 'errors/', 'logs/', 'storage/', 
			'cuenta/', 'admin/', 'moderacion/', 'monitor/', 'mensajes/', 'favoritos.php', 
			'borradores.php', 'agregar/', 'agregar.php', 'ajax_files/', 'password/', 
			'validar/', 'fotos/editar/', 'fotos/agregar/', '*.webp', '*.js', '*.css', '*.txt', 
			'*.php', '*.html'
		];
		foreach ($disallow as $dis) {
			$robots .= "Disallow: " . (substr($dis, 0, 1) !== '*' ? "/$dis" : $dis) . "\n";
		}
		if (file_exists(TS_ROOT . "sitemap.xml")) {
			$robots .= "Sitemap: {$tsCore->settings['url']}/sitemap.xml\n";
		}
		if (!file_exists($this->robots)) {
			file_put_contents($this->robots, trim($robots));
		}
	}

}
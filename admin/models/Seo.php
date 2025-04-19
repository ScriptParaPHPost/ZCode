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

if (!defined('ZCODEV3')) exit('No se permite el acceso directo al script');

class Seo {

	public $robots;
	
	public $seo;

	private $core;

	public function __construct() {
		$this->core = new tsCore;
		$this->robots = BASEPATH . 'robots.txt';
		$this->seo = $this->getSeo();
	}

	# ===================================================
	# SEO
	# * getSEO() :: Obtenemos toda la informacion
	# * saveSEO() :: Guardamos la informacion
	# * addRobotsTXT() :: Generamos el robots.txt
	# ===================================================
	public function getSeo() {
		$sql = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT seo_id, seo_titulo, seo_descripcion, seo_portada, seo_keywords, seo_robots, seo_sitemap, seo_google_verification, seo_google_verification_active, seo_google_analytics FROM @seo WHERE seo_id = 1'));
		if($sql === null) return [];
		return $sql;
	}

	public function saveSEO() {
		$data = [];
		foreach($_POST as $k => $val) {
			if(!empty($val)) {
				$data[$k] = is_numeric($val) ? (int)$val : (is_array($val) ? json_encode($val, JSON_FORCE_OBJECT) : $val);
			}
		}
		$update = $this->core->getIUP($data, 'seo_');
		if(updateRecordById([__FILE__, __LINE__], '@seo', $update, 'seo_id = 1')) {
			return '1: Configuarciones guardadas.';
		}
		return '0: Hubo un error al guardar las configuraciones.';
	}

	public function addRobotsTXT() {
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
		if (file_exists(BASEPATH . "sitemap.xml")) {
			$robots .= "Sitemap: {$this->core->settings['url']}/sitemap.xml\n";
		}
		if (!file_exists($this->robots)) {
			file_put_contents($this->robots, trim($robots));
		}
	}

}
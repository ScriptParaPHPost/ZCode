<?php

if (!defined('TS_HEADER'))
	 exit('No se permite el acceso directo al script');
/**
 * Modelo para la adminitración
 *
 * @name    c.seo.php
 * @author  ZCode | PHPost
 */
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
	# * getNoticia() :: Obtenemos la noticia por ID
	# * delNoticia() :: Eliminamos la noticia por ID
	# * newNoticia() :: Creamos una nueva notica
	# * editNoticia() :: Editamos la noticia
	# ===================================================
	public function getSeo() {
		$tsCore = new tsCore;
		$sql = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT seo_id, seo_titulo, seo_descripcion, seo_portada, seo_favicon, seo_keywords, seo_images, seo_robots_data, seo_robots, seo_sitemap FROM @seo WHERE seo_id = 1'));
		if($sql == null) return [];
		$robots = json_decode($sql['seo_robots_data'], true);
		$sql['robots_name'] = $robots['name'];
		$sql['robots_content'] = $robots['content'];
		$sql['seo_images'] = empty($sql['seo_images']) ? : json_decode($sql['seo_images'], true);

		return $sql;
	}

	public function addRobotsTXT() {
		global $tsCore;
		$robots = "User-agent: *\n";
		$disallow = ['admin/', 'app/', 'assets/', 'auth/', 'config/', 'errors/', 'logs/', 'storage/', 'cuenta/', 'admin/', 'moderacion/', 'monitor/', 'mensajes/', 'favoritos.php', 'borradores.php', 'agregar/', 'agregar.php', 'ajax_files/', 'password/', 'validar/', 'fotos/editar/', 'fotos/agregar/', '*.webp', '*.js', '*.css', '*.txt', '*.php', '*.html'];
		foreach($disallow as $dis) {
			$slash = substr($dis, 0, 1);
			$dis = ($slash !== '*') ? "/$dis" : $dis;
			$robots .= "Disallow: $dis\n";
		}
		if(file_exists(TS_ROOT . "sitemap.xml")) {
			$robots .= "Sitemap: {$tsCore->settings['url']}/sitemap.xml\n";
		}
		if(!file_exists($this->robots)) file_put_contents($this->robots, trim($robots));
	}

}
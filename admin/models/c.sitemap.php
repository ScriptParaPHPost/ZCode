<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Clase para el manejo de sitemap
 *
 * @name    c.sitemap.php
 * @author  Miguel92
 */

class tsSitemap {

	protected $sitemap = 'sitemap.xml';

	private $viewXML = false;

	private $show_file = false;

	public function __construct() {
		$this->sitemap = TS_ROOT . $this->sitemap;
	}

	public function syncSitemap() {
		if(file_exists($this->sitemap)) unlink($this->sitemap);
		$this->addSitemap();
		return true;
	}

	private function dateSitemap(int $date = 0) {
		return date('Y-m-d\TH:i:sP', $date);
	}

	private function setURLBasicOfSystem() {
		global $tsCore;
		$q = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT stats_time_foundation FROM w_stats WHERE stats_no = 1"))['stats_time_foundation'];
		$time = $this->dateSitemap($q);
		return [
			"{$tsCore->settins['url']}/" => ["never", $time, '1.0'], 
			"{$tsCore->settins['url']}/buscador/" => ["never", $time, '0.80'],
			"{$tsCore->settins['url']}/fotos/" => ["hourly", $time, '0.80'], 
			"{$tsCore->settins['url']}/posts/" => ["hourly", $time, '0.80'], 
			"{$tsCore->settins['url']}/tops/" => ["never", $time, '0.80'], 
			"{$tsCore->settins['url']}/tops/posts" => ["hourly", $time, '0.80'], 
			"{$tsCore->settins['url']}/tops/usuarios" => ["daily", $time, '0.80'],
			"{$tsCore->settins['url']}/usuarios/" => ["daily", $time, '0.80'],
			"{$tsCore->settins['url']}/pages/ayuda/" => ["never", $time, '0.80'], 
			"{$tsCore->settins['url']}/pages/chat/" => ["never", $time, '0.80'], 
			"{$tsCore->settins['url']}/pages/dmca/" => ["never", $time, '0.80'],  
			"{$tsCore->settins['url']}/pages/privacidad/" => ["never", $time, '0.80'],  
			"{$tsCore->settins['url']}/pages/protocolo/posts" => ["never", $time, '0.80'],  
			"{$tsCore->settins['url']}/pages/terminos-y-condiciones/" => ["never", $time, '0.80']
		];
	}

	public function setURLPostsCreated() {
		global $tsCore;
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT post_id, post_title, post_date, c_seo FROM @posts LEFT JOIN @posts_categorias ON cid = post_category"));
		$add = [];
		foreach($data as $pid => $post) {
			$add[$tsCore->createLink('post', $post['post_id'])] = ["monthly", $this->dateSitemap($post['date']), '0.50'];
		}
		return $data;
	}

	private function getSitemapDB() {
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT id, url, frecuencia, fecha, prioridad FROM @sitemap"));
		return $data;
	}

	public function sitemap_generator($urls, $mode = true) {
	 	$xmlString = '<?xml version="1.0" encoding="UTF-8"?>
	 	<urlset
	 	   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	 	   xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
	 	   xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
	 	   xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	 	foreach ($urls as $key => $value) {
		   $xmlString .=  '<url>';
		   $xmlString .=  '<loc>'.$value.'</loc>';
		   $xmlString .=  ($mode) ? '<changefreq>daily</changefreq>' : '<lastmod>'.$value['time'].'</lastmod>';
		   $xmlString .=  '</url>';
	 	}

	 	$xmlString .= '</urlset>';

	 	if($this->viewXML) {
	 		var_dump($xmlString);
	 	}

	 	$dom = new DOMDocument;
	 	$dom->preserveWhiteSpace = false;
	 	$dom->loadXML($xmlString);
	 	$dom->save($this->sitemap);

	 	if($this->show_file) return $this->sitemap;
	}

	public function addSitemap() {
		var_dump($this->setURLBasicOfSystem());
		var_dump($this->setURLPostsCreated());
		#$this->sitemap_generator($urls);
	}

}
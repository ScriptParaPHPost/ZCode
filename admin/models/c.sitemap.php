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

	public $frecuencias = ['never', 'always', 'daily', 'hourly', 'weekly', 'monthly', 'yearly'];

	public $prioridades = ['1.0', '0.9', '0.8', '0.7', '0.6', '0.5', '0.4', '0.3', '0.2', '0.1', '0'];

	private function revertFrecuencia(string $option = '') {
		$data = ['never' => 0, 'always' => 1, 'daily' => 2, 'hourly' => 3, 'weekly' => 4, 'monthly' => 5, 'yearly' => 6];
		return $data[$option];
	}

	private function revertPrioridad($option = '') {
		$data = [
			"1.0" => 0, 
			"0.9" => 1, 
			"0.8" => 2, 
			"0.7" => 3,
			"0.6" => 4,
			"0.5" => 5,
			"0.4" => 6,
			"0.3" => 7,
			"0.2" => 8,
			"0.1" => 9, 
			"10" => 10
		];
		return $data[$option];
	}

	/**
	 * Constructs a new instance.
	 */
	public function __construct() {
		$this->sitemap = TS_ROOT . $this->sitemap;
	}

	/**
	 * { function_description }
	 *
	 * @return     bool  ( description_of_the_return_value )
	 */
	public function syncSitemap() {
		if(file_exists($this->sitemap)) unlink($this->sitemap);
		$this->addSitemap();
		return true;
	}

	/**
	 * { function_description }
	 *
	 * @param      int     $date   The date
	 *
	 * @return     <type>  ( description_of_the_return_value )
	 */
	private function dateSitemap(int $date = 0) {
		return date('Y-m-d\TH:i:sP', $date);
	}

	/**
	 * Sets the url basic of system.
	 *
	 * @param      <type>  $data   The data
	 */
	private function setURLBasicOfSystem(&$data) {
		global $tsCore;
		$q = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT stats_time_foundation FROM @stats WHERE stats_no = 1"))['stats_time_foundation'];
		$time = time();
		$data["{$tsCore->settings['url']}/"] = ["never", $time, '1.0']; 
		$data["{$tsCore->settings['url']}/buscador/"] = ["never", $time, '0.80'];
		$data["{$tsCore->settings['url']}/fotos/"] = ["hourly", $time, '0.80']; 
		$data["{$tsCore->settings['url']}/posts/"] = ["hourly", $time, '0.80']; 
		$data["{$tsCore->settings['url']}/tops/"] = ["never", $time, '0.80']; 
		$data["{$tsCore->settings['url']}/tops/posts"] = ["hourly", $time, '0.80']; 
		$data["{$tsCore->settings['url']}/tops/usuarios"] = ["daily", $time, '0.80'];
		$data["{$tsCore->settings['url']}/usuarios/"] = ["daily", $time, '0.80'];
		$data["{$tsCore->settings['url']}/pages/ayuda/"] = ["never", $time, '0.80']; 
		$data["{$tsCore->settings['url']}/pages/chat/"] = ["never", $time, '0.80']; 
		$data["{$tsCore->settings['url']}/pages/dmca/"] = ["never", $time, '0.80'];  
		$data["{$tsCore->settings['url']}/pages/privacidad/"] = ["never", $time, '0.80'];  
		$data["{$tsCore->settings['url']}/pages/protocolo/posts"] = ["never", $time, '0.80'];  
		$data["{$tsCore->settings['url']}/pages/terminos-y-condiciones/"] = ["never", $time, '0.80'];
	}

	public function setURLPostsCreated(&$data) {
		global $tsCore;
		$getdata = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT post_id, post_title, post_date, c_seo FROM @posts LEFT JOIN @posts_categorias ON cid = post_category"));

		foreach($getdata as $pid => $post) {
			$data[$tsCore->createLink('post', $post['post_id'])] = ["monthly", (int)$post['post_date'], '0.50'];
		}

	}

	public function getSitemap() {
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT id, url, frecuencia, fecha, prioridad FROM @sitemap"));
		return $data;
	}

	/**
	 * { function_description }
	 *
	 * @param      <type>  $urls   The urls
	 * @param      bool    $mode   The mode
	 *
	 * @return     <type>  ( description_of_the_return_value )
	 */
	public function sitemap_generator($urls) {
	 	$xmlString = '<?xml version="1.0" encoding="UTF-8"?>
	 	<urlset
	 	   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	 	   xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
	 	   xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
	 	   xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	 	foreach ($urls as $key => $value) {
		   $xmlString .=  '<url>';
		   $xmlString .=  "<loc>{$key}</loc>";
		   $xmlString .=  "<changefreq>{$value[0]}</changefreq>";
		   $xmlString .=  "<lastmod>{$this->dateSitemap($value[1])}</lastmod>";
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
		$urls = [];
		$this->setURLBasicOfSystem($urls);
		$this->setURLPostsCreated($urls);
		//
		db_exec([__FILE__, __LINE__], 'query', "TRUNCATE @sitemap");
		db_exec([__FILE__, __LINE__], 'query', "ALTER TABLE @sitemap AUTO_INCREMENT 1");
		foreach($urls as $url => $data) {
			db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @sitemap (url, frecuencia, fecha, prioridad) VALUES ('$url', '$data[0]', $data[1], '$data[2]')");
		}
		$this->sitemap_generator($urls);
	}

	/*
	 * AGREGAR URL
	 */
	public function newUrlSitemap() {
		global $tsCore;
		array_pop($_POST);
		$url = $tsCore->setSecure($_POST['url']);
		$frecuencia = $this->frecuencias[$_POST['frecuencia']];
		$prioridad = $this->prioridades[$_POST['prioridad']];
		$date = time();
		if(db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @sitemap (url, frecuencia, fecha, prioridad) VALUES ('$url', '$frecuencia', $date, '$prioridad')")) {
			#$this->setSiteMapUpdate();
			return true;
		}
	}

	/*
	 * EDITAMOS
	 */
	public function SitemapEditID() {
		$id = (int)$_GET['id'];
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT url, frecuencia, fecha, prioridad FROM @sitemap WHERE id = $id"));
		$data['frecuencia'] = $this->revertFrecuencia($data['frecuencia']);
		$data['prioridad'] = $this->revertPrioridad($data['prioridad']);
		return $data;
	}
	public function SitemapSaveID() {
		$id = (int)$_GET['id'];
		$prioridad = $this->prioridades[$_POST['prioridad']];
		$frecuencia = $this->frecuencias[$_POST['frecuencia']];
		return (db_exec([__FILE__, __LINE__], 'query', "UPDATE @sitemap SET url = '{$_POST['url']}', prioridad = '$prioridad', frecuencia = '$frecuencia' WHERE id = $id"));
	}
	/*
	 * CONFIGURACION SITEMAP
	 */
	public function setSettings() {
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT register_post, register_foto, update_post, update_foto FROM @sitemap_control WHERE sid = 1"))[0];
		foreach($data as $k => $val) $data[$k] = (int)$val;
		return $data;
	}

	public function saveSettings() {
		if(isset($_POST['save'])) {
			array_pop($_POST);
			foreach($_POST as $k => $val) $_POST[$k] = (int)$val;
			return (db_exec([__FILE__, __LINE__], 'query', "UPDATE @sitemap_control SET register_post = '{$_POST['register_post']}', register_foto = '{$_POST['register_foto']}', update_post = '{$_POST['update_post']}', update_foto = '{$_POST['update_foto']}' WHERE sid = 1"));
		}
	}

}
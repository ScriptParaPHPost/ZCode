<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Clase para el manejo de sitemap
 *
 * @name    c.sitemap.php
 * @author  Miguel92
 */

class tsSitemap {

	private $register_post;

	private $register_foto;

	private $update_post;

	private $update_foto;

	/**
	 * Constructs a new instance.
	 */
	public function __construct() {
		$sitemap = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT register_post, register_foto, update_post, update_foto FROM @sitemap_control WHERE sid = 1"));
		// Si estan activos seran true!
		$this->register_post = ((int)$sitemap['register_post'] === 1);
		$this->register_foto = ((int)$sitemap['register_foto'] === 1);
		$this->update_post = ((int)$sitemap['update_post'] === 1);
		$this->update_foto = ((int)$sitemap['update_foto'] === 1);
	}

	/**
	 * Creates an url.
	 *
	 * @param      string  $action  The action
	 * @param      int     $id      The identifier
	 *
	 * @return     array   ( description_of_the_return_value )
	 */
	private function createUrl(string $action = 'post', int $id = 0) {
		global $tsCore;
		if($action === 'post') {
			$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT post_title, post_date, post_update, c_seo FROM @posts LEFT JOIN @posts_categorias ON cid = post_category WHERE post_id = $id"));
			$title = $tsCore->setSEO($data['post_title']);
			return [
				'date' => $data['post_date'],
				'update' => $data['post_update'],
				'url' => $tsCore->settings['url'] . "/posts/{$data['c_seo']}/$id/$title.html"
			];
		}
	}

	/**
	 * Adds a sitemap table.
	 *
	 * @param      string  $url         The url
	 * @param      string  $frecuencia  The frecuencia
	 * @param      int     $fecha       The fecha
	 * @param      string  $prioridad   The prioridad
	 *
	 * @return     bool    ( description_of_the_return_value )
	 */
	private function addSitemapTable(string $url = '', string $frecuencia = '', int $fecha = 0, string $prioridad = '') {
		if(db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @sitemap (url, frecuencia, fecha, prioridad) VALUES ('$url', '$frecuencia', $fecha, '$prioridad')")) {
			return true;
		}
	}

	/**
	 * { function_description }
	 *
	 * @param      string  $url         The url
	 * @param      string  $frecuencia  The frecuencia
	 * @param      int     $fecha       The fecha
	 * @param      string  $prioridad   The prioridad
	 *
	 * @return     bool    ( description_of_the_return_value )
	 */
	private function updateSitemapTable(string $url = '', string $frecuencia = '', int $fecha = 0, string $prioridad = '') {
		if(db_exec([__FILE__, __LINE__], 'query', "UPDATE @sitemap SET frecuencia = '$frecuencia', fecha = $fecha, prioridad = '$prioridad' WHERE url = '$url'")) {
			return true;
		}
	}

	/**
	 * Adds a sitemap information.
	 *
	 * @param      string  $type   The type
	 * @param      int     $id     The identifier
	 */
	public function addSitemapInfo(string $type = 'add', int $id = 0) {
		if($this->register_post) {
			$data = $this->createUrl('post', $id);
			if($type === 'add') {
				$this->addSitemapTable($data['url'], 'monthly', $data['date'], '0.50');
			} else {
				$this->updateSitemapTable($data['url'], 'monthly', $data['post_update'], '0.40');
			}
		}
	}

}
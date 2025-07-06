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

use admin\models\Core;

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

class Socials {

	protected Core $Core;

	private string $urlBase;

	public function __construct() {
		$this->Core = new Core;
		$this->urlBase = $this->Core->setRoutes('url');
	}

	private function redirect_uri_create(string $param = '/'): string {
		return $this->urlBase . ($param === '/' ? $param : strtolower($param) . '.php');
	}

	private function getID(): int {
	   $input = [
	      [INPUT_GET, 'id'],
	      [INPUT_POST, 'social_id'],
	      [INPUT_POST, 'id']
	   ];
	   foreach ($input as [$method, $key]) {
	      $id = filter_input($method, $key, FILTER_VALIDATE_INT);
	      if ($id !== false && $id !== null) {
	         return (int)$id;
	      }
	   }
	   return 0;
	}

	private function getData(?string $param = '') {
		$social = [];
		$data = ['name', 'client_id', 'client_secret'];
		foreach($data as $item) {
			$social[$item] = $this->Core->setSecure(filter_input(INPUT_POST, "social_$item", FILTER_UNSAFE_RAW));
		}
		return $social[$param];
	}

	public function getSocials() {
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT social_id, social_name, social_client_id, social_client_secret, social_redirect_uri FROM @social'));
		foreach($data as $key => $social) {
			$data[$key]['social_redirect_uri'] = $this->redirect_uri_create("/{$social['social_name']}");
		}
		return $data;
	}

	public function newSocial() {
		// Guardamos
		$name = $this->getData('name');
		if(addDataToTable([__FILE__, __LINE__], '@social', [
			'name' => $name,
			'client_id' => $this->getData('client_id'),
			'client_secret' => $this->getData('client_secret'),
			'redirect_uri' => $this->redirect_uri_create("/$name")
		], 'social_')) return true;
	}

	public function getSocial() {
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT social_id, social_name, social_client_id, social_client_secret, social_redirect_uri FROM @social WHERE social_id = {$this->getID()}"));
		return $data;
	}

	public function saveSocial() {
		return (db_exec([__FILE__, __LINE__], 'query', "UPDATE @social SET 
			social_client_id = '{$this->getData('client_id')}', 
			social_client_secret = '{$this->getData('client_secret')}' 
			WHERE social_id = {$this->getID()}"));
	}

	public function eliminarRed() {
		$id = $this->getID();
		if($id === 0) return false;
		return (db_exec([__FILE__, __LINE__], 'query', "DELETE FROM @social WHERE social_id = $id"));
	}

}
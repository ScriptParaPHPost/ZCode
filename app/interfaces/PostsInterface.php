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

namespace app\interfaces;

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

interface PostsInterface {

	public function short_url_post();
	
	public function getTitles(string $from = '');
	
	public function setNP();
	
	public function getPost();
	
	public function getAutor(int $user_id = 0);
	
	public function getPunteador(bool $puntuador = false);
	
	public function deletePost();
	
	public function deleteAdminPost();
	
	public function getRelated(string $tags = '');
	
	public function getPostAutor(int $uid = 0);
	
	public function votarPost();

	public function subirRango(int $user_id = 0, int $post_id = 0);

}
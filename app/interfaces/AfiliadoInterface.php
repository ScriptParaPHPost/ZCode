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

interface AfiliadoInterface {

	public function getAfiliados(string $type = 'home');
	
	public function getAfiliado(string $type = 'home');
	
	public function newAfiliado();
	
	public function EditarAfiliado();
	
	public function DeleteAfiliado();
	
	public function SetActionAfiliado();
	
	public function urlOut();
	
	public function urlIn();
	
}
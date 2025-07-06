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

interface AutenticarInterface {

	public function setSession();

	public function getUserLoginData(string $usuario = '');

	public function sessionUpdate(int $id = 0, bool $rem = true, ?string $twofactor = null);

	public function login(string $usuario = '', string $password = '', bool $recordar = false, bool $redirectTo = false);

   public function logout();

  	public function getSessionID();

   public function getTimeNow();

   public function getIpAddress();

	public function clearSession();

}
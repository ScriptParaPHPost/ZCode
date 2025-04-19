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

interface UserInterface {
    public function puntos_actualizados();
    public function DarMedalla();
    public function loadUser($login = FALSE);
    public function deleteUserOutTime(int $opcion = 0, int $time = 0);
    public function unlinkAccount();
    public function validateTwoFactor();
    public function logoutUser(int $user_id = 0, bool $redirectTo = false);
    public function userActivate(int $tsUserID = 0, string $tsKey = '');
    public function getUserBanned();
    public function getUserID(string $tsUser = '');
    public function getUserName(int $user_id = 0);
    public function getUserIsVerified(string $user_name = '');
    public function getUserRango(int $user_id = 0, string $type = 'r_name');
    public function iFollow(int $user_id = 0);
    public function isUserBloqued(int $b_user = 0, int $b_auser = 0);
    public function getUsuarios();
}

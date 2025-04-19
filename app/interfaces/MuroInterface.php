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

interface MuroInterface {
    public function getPrivacity(int $user_id = 0, string $username = null, $follow = NULL, $yfollow = NULL);
    public function ajaxCheck($return = false, $urlin = null);
    public function streamPost();
    public function getNews($start = 0, $limit = 10);
    public function getWall($user_id, $start = 0);
    public function getPubExtras($pub_id, $type = 'likes', $likes = 0);
    public function likePost();
}

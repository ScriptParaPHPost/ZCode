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
    public function getPrivacity(int $user_id = 0, string $username = '', int $follow = 0, int $yfollow = 0);
    public function ajaxCheck(bool $return = false, string $urlin = '');
    public function streamPost();
    public function getNews(int $start = 0, int $limit = 10);
    public function getWall(int $user_id = 0, int $start = 0);
    public function getPubExtras(int $pub_id = 0, string $type = 'likes', int $likes = 0);
    public function likePost();
}

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

interface TopsInterface {
    public function getHomeTopPosts();
    public function getHomeTopUsers();
    public function getTopUsers(int $fecha = 0, int $cat = 0);
    public function getTopPosts(int $fecha = 0, int $cat = 0);
    public function getTopPostsQuery(array $data = []);
    public function getHomeTopPostsQuery(array $date = []);
    public function getHomeTopUsersQuery(array $date = []);
    public function getStats();
    public function updateActivity();
    public function cleanInactiveUsers();
    public function setTime(int $fecha = 0);
}

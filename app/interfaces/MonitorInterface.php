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

interface MonitorInterface {
    public function setAviso(int $user_id = 0, string $subject = '(sin asunto)', string $body = '', int $type = 0);
    public function getAvisos();
    public function readAviso(int $av_id = 0);
    public function delAviso(int $av_id = 0);
    public function setNotificacion(int $type = 0, int $user_id = 0, int $obj_user = 0, int $obj_uno = 0, int $obj_dos = 0, int $obj_tres = 0);
    public function setFollowNotificacion(int $notType = 0, int $f_type = 0, int $user_id = 0, int $obj_uno = 0, int $obj_dos = 0, array $excluir = []);
    public function setMuroRepost(int $pub_id = 0, int $p_user = 0, int $p_user_pub = 0);
    public function getNotificaciones(bool $unread = false);
    public function makeConsulta(array $data = []);
    public function setFollow();
    public function setUnFollow();
    public function getFollowVars();
    public function getFollows(string $type = '', int $user_id = 0);
    public function setSpam();
    public function setFiltro();
}

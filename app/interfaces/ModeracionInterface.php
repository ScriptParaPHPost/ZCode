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

interface ModeracionInterface {
    public function multiAction(string $action = '');
    public function getMods();
    public function getDenuncias($type = 'posts');
    public function getDenuncia($type = 'posts');
    public function getContenido();
    public function getPreview(int $pid = 0);
    public function rebootPost(int $pid = 0);
    public function OcultarPost(int $pid = 0, string $razon = null);
    public function rebootMps(int $mid = 0);
    public function rebootFoto(int $fid = 0);
    public function deletePost(int $pid = 0);
    public function deleteMps(int $mid = 0);
    public function deleteFoto($fid);
    public function setSticky(int $post_id = 0);
    public function setOpenClosed(int $post_id = 0);
    public function getSuspendidos();
    public function banUser(int $user_id = 0);
    public function rebootUser(int $user_id = 0, string $type = 'unban');
    public function setHistory($action, $type, $data);
    public function getPospelera();
    public function getFopelera();
    public function getComentariosD();
    public function getPostsD();
    public function getHistory($type);
}

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

interface UploadInterface {
    public function newUpload(int $type = 1);
    public function uploadFile(array $file = [], string $type = 'file');
    public function uploadUrl();
    public function validFile(array $file = [], string $type = 'file');
    public function sendFile(array $file = [], string $name = '');
    public function copyFile(array $file = [], string $name = '');
    public function createImage(array $file = [], string $name = '');
    public function cropAvatar(string $key = '');
    public function deleteFile(string $file = '');
    public function uploadImagen(string $params = '');
    public function setParams(string $url = '');
    public function getImagenUrl(string $code = '');
}

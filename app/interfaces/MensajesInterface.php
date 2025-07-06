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

interface MensajesInterface {
    public function getValid();
    public function getMensajes(int $type = 1, bool $unread = false, string $where = 'normal');
    public function readMensaje();
    public function getMensajesControl();
    public function getDataMensajePrivado();
    public function getLeerMensajePrivado();
}

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

interface VisitasInterface {
    public function wasVisited(int $id = 0, int $type = 0, string $limit = '');
    public function recordarVisita(int $id = 0, int $type = 0, int $uid = 0);
    public function ultimasVisitas(int $id = 0, int $total = 15);
    public function actualizarVisitas(int $id = 0, int $uid = 0, int $type = 0);
}

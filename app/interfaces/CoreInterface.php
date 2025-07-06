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

interface CoreInterface {
    public function getSettings();
    public function setRoutes(string $param = '', string $extra = '');
    public function getNovemods();
    public function getCategorias();
    public function imageCat(string $cat = '');
    public function getTema();
    public function getNews();
    public function parseBadWords(string $censurar = '', bool $type = FALSE);
    public function setLevel(int $tsLevel = 0, bool $message = false);
    public function redireccionar(string $page = '', string $subpage = '', string $param = '');
    public function redirectTo(string $tsDir = '/');
    public function getDomain();
    public function currentUrl();
    public function setSecure(string $string = '', bool $xss = false);
    public function antiFlood(bool $print = true, string $type = 'post', string $msg = '');
    public function setSEO(string $string = '', bool $lower = false);
    public function parseBBCode(string $bbcode = '', string $type = 'normal');
    public function setMenciones(string $html = '');
    public function setPageLimit(int $tsLimit = 0, $start = false, int $tsMax = 0);
    public function setMaximos(int $tsLimit = 0, int $tsMax = 0);
    public function getPages(int $tsTotal = 0, int $tsLimit = 0);
    public function getPagination(int $total = 0, int $per_page = 10);
    public function pageIndex(string $base_url = '', int $max_value = 0, int $num_per_page = 0, bool $flexible_start = false);
    public function setHace(int $fecha = 0, bool $show = false);
    public function getUrlContent(string $tsUrl = '');
    public function getIP();
    public function validarIP();
    public function executeIP();
}

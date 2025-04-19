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
    public function setRoutes(?string $param = null, ?string $extra = null);
    public function getNovemods();
    public function getCategorias();
    public function imageCat(string $cat = '');
    public function getTema();
    public function getNews();
    public function parseBadWords(string $censurar = '', bool $type = FALSE);
    public function setLevel(?int $tsLevel = null, bool $message = false);
    public function redireccionar(string $page = '', string $subpage = '', string $param = '');
    public function redirectTo(string $tsDir = '/');
    public function getDomain();
    public function currentUrl();
    public function setSecure($string = null, bool $xss = false);
    public function antiFlood($print = true, $type = 'post', $msg = '');
    public function setSEO($string, $lower = false);
    public function parseBBCode($bbcode, $type = 'normal');
    public function setMenciones($html);
    public function setPageLimit($tsLimit, $start = false, $tsMax = 0);
    public function setMaximos(int $tsLimit = 0, int $tsMax = 0);
    public function getPages(int $tsTotal = 0, int $tsLimit = 0);
    public function getPagination($total, $per_page = 10);
    public function pageIndex($base_url, $max_value, $num_per_page, $flexible_start = false);
    public function setHace(int $fecha = 0, $show = false);
    public function getUrlContent(string $tsUrl);
    public function getIP();
    public function validarIP();
    public function executeIP();
}

<?php 

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 3.1.18
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

namespace app\interfaces;

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

interface SmartyInterface {
    public function output(bool $loadFilter = false);
    public function loadAllTemplates(string $tema = '', string $tsPage = '');
    public function loadTemplate(string $page = '');
    public function clearCompiled(string $template = '');
}

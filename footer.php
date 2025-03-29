<?php 

if (!defined('ZCODE2')) exit('No se permite el acceso directo al script');

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

// P�gina solicitada
$smarty->assign("tsPage", $tsPage);
# Por si quieren cambiar la p�gina de error
# Si no encuentra la plantilla t.$tsPage.tpl
# Mostrar esta p�gina
$smarty->template_error = '404.html';

$smarty->loadAllTemplates(TS_TEMA, $tsPage);
$smarty->loadTemplate($tsPage);
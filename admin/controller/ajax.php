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

$tsPage = "";

$tsLevel = 0;

$tsAjax = empty($_GET['ajax']) ? 0 : 1;

include "../header.php"; 

$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['slogan']; 

$action = htmlspecialchars($_GET['action'] ?? '');
$action_type = explode('-', $action)[0];

// Determinar el archivo necesario
$file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'ajax/ajax.' . $action_type . '.php';

// Verificar si el archivo existe y luego incluirlo
if ($file AND file_exists($file)) {
	include $file;
} else {
	die("0: No se encontró el archivo solicitado: " . htmlspecialchars($file));
}

if(empty($tsAjax)) {

	$smarty->assign("tsTitle",$tsTitle);
	
	include BASEPATH . 'footer.php';
	
}
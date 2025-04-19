<?php 

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package		ZCode
 * @author 		Miguel92
 * @copyright 	2024 - 2025
 * @version 	3.1.18
 * @link 		https://zcodev.alwaysdata.net/ (DEMO)
 * @link 		https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link 		https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

use app\models\Registro;

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');


// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCI�N
$files = [
	'registro-form' => ['n' => 1, 'p' => 'form'],
	'registro-check-nick' => ['n' => 1, 'p' => ''],
	'registro-check-email' => ['n' => 1, 'p' => ''],
	'registro-geo' => ['n' => 0, 'p' => ''],
	'registro-nuevo' => ['n' => 1, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.registro.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1 || (isset($_POST['csrf_token']) && $_POST['csrf_token'] !== $_SESSION['csrf'])) { 
	echo '0: '.($_POST['csrf_token'] !== $_SESSION['csrf']) ? 'CSRF inválido' : $tsLevelMsg; 
	die(); 
}
	
// CLASE
$tsReg = new Registro();

// CODIGO
switch($action){
	case 'registro-form':				
		if($tsCore->settings['c_reg_active'] == 0) {
			$tsAjax = '1';
			echo '0: <div class="dialog_box">El registro se encuentra momentaneamente desactivado.</div>';
		} else {    
			// SOLO MENORES DE 84 A�OS xD Y MAYORES DE...
			$now_year = date("Y", time());
			// 100a�os - 16a�os = 84a�os
			$edad = (int)$tsCore->settings['c_allow_edad'];
			$max_year = 100 - $edad;
			$start_year = (int)$now_year - (int)$max_year;
			$end_year = (int)$now_year - (int)$tsCore->settings['c_allow_edad'];
			//
			$smarty->assign("tsMax", (int)$max_year);
			$smarty->assign("tsMaxY", (int)$start_year);
			$smarty->assign("tsEndY", (int)$end_year);
 			$smarty->assign('OAuth', $tsCore->OAuth());
		}
	break;
	case 'registro-check-nick':	
	case 'registro-check-email':
		echo $tsReg->checkUserEmail();
	break;
	case 'registro-geo':
		include TS_UTILS . "geodata.php";
		$pais = isset($_GET['pais_code']) ? htmlspecialchars($_GET['pais_code']) : '';
		//
		if($pais) $html = '1: ';
		else $html = '0: El campo <b>pais_code</b> es requerido para esta operacion';
		foreach($estados[$pais] as $key => $estado) 
			$html .= '<option value="'.($key+1).'">'.$estado.'</option>'."\n";
		//
		echo (strlen($html) > 3) ? $html : '0: C�digo de pais incorrecto.';
	break;
	case 'registro-nuevo':
		$result = $tsReg->registerUser();
		echo $result;
	break;
}
<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Controlador AJAX
 *
 * @name    ajax.registro.php
 * @author  Miguel92 & PHPost.es
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCIÓN
	
	$files = array(
	
		'registro-form' => array('n' => 1, 'p' => 'form'),
		
		'registro-check-nick' => array('n' => 1, 'p' => ''),
		
		'registro-check-email' => array('n' => 1, 'p' => ''),
		
		'registro-geo' => array('n' => 0, 'p' => ''),
		
		'registro-nuevo' => array('n' => 1, 'p' => ''),
		
	);

/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	// REDEFINIR VARIABLES
	
	$tsPage = 'php_files/p.registro.'.$files[$action]['p'];
	
	$tsLevel = $files[$action]['n'];
	
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/
	
	// DEPENDE EL NIVEL
	
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg; die();}
	
	// CLASE
	require_once TS_CLASS . 'c.registro.php';
	$tsReg = new tsRegistro();

	// CODIGO
	switch($action){
		case 'registro-form':				
			if($tsCore->settings['c_reg_active'] == 0) {
				$tsAjax = '1';
				echo '0: <div class="dialog_box">El registro se encuentra momentaneamente desactivado.</div>';
			} else {    
				require_once TS_EXTRA . "datos.php";
				// SOLO MENORES DE 84 AÑOS xD Y MAYORES DE...
				$now_year = date("Y", time());
				// 100años - 16años = 84años
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
			include("../ext/geodata.php");
			$pais = isset($_GET['pais_code']) ? htmlspecialchars($_GET['pais_code']) : '';
			//
			if($pais) $html = '1: ';
			else $html = '0: El campo <b>pais_code</b> es requerido para esta operacion';
			foreach($estados[$pais] as $key => $estado) 
				$html .= '<option value="'.($key+1).'">'.$estado.'</option>'."\n";
			//
			echo (strlen($html) > 3) ? $html : '0: Código de pais incorrecto.';
		break;
		case 'registro-nuevo':
			$result = $tsReg->registerUser();
			echo $result;
		break;
	}
<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Controlador AJAX
 *
 * @name    ajax.database.php
 * @author  Miguel92
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCIÓN
	$files = array(
		'database-analyze' => array('n' => 2, 'p' => ''),
		'database-optimize' => array('n' => 2, 'p' => ''),
		'database-repair' => array('n' => 2, 'p' => ''),
		'database-check' => array('n' => 2, 'p' => ''),
		'database-all' => array('n' => 2, 'p' => ''),
		'database-backup' => array('n' => 2, 'p' => ''),
	);


/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	// REDEFINIR VARIABLES
	$tsPage = 'php_files/p.database.'.$files[$action]['p'];
	$tsLevel = $files[$action]['n'];
	$tsAjax = empty($files[$action]['p']) ? 1 : 0;

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/
	
	// DEPENDE EL NIVEL
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1) { echo '0: '.$tsLevelMsg; die();}
    // CLASE
	include TS_CLASS . "c.database.php";
	$tsDatabase = new tsDatabase();

	// CODIGO
	switch($action) {
		case 'database-analyze':
		case 'database-optimize':
		case 'database-repair':
		case 'database-check':
			//<---
			$type = strtoupper(explode('-', $action)[1]);
         echo $tsDatabase->handleAction($type);
			//--->
		break;
		case 'database-all':
			//<---
         echo $tsDatabase->allActions();
			//--->
		break;
		case 'database-backup':
			//<---
         echo $tsDatabase->createBackup();
			//--->
		break;
	}
?>
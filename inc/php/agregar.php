<?php 
/**
 * Controlador
 *
 * @name    agregar.php
 * @author  Miguel92 & PHPost.es
*/

/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	$tsPage = "agregar";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

	$tsLevel = 2;		// NIVEL DE ACCESO A ESTA PAGINA. => VER FAQs

	$tsAjax = empty($_GET['ajax']) ? 0 : 1; // LA RESPUESTA SERA AJAX?
	
	$tsContinue = true;	// CONTINUAR EL SCRIPT
	
/*++++++++ = ++++++++*/

	include realpath('../../') . DIRECTORY_SEPARATOR . "header.php";  // INCLUIR EL HEADER

	$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['slogan']; 	// TITULO DE LA PAGINA ACTUAL

/*++++++++ = ++++++++*/
	
	// VERIFICAMOS EL NIVEL DE ACCSESO ANTES CONFIGURADO
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1){	
		$tsPage = 'aviso';
		$tsAjax = 0;
		$smarty->assign("tsAviso",$tsLevelMsg);
		//
		$tsContinue = false;
	}
	//
	if($tsContinue){
/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	$action = $_GET['action'];

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/
	include TS_CLASS . "c.agregar.php";
	$tsAgregar = new tsAgregar();
	$smarty->assign("tsCategorias", $tsAgregar->getCategorias());

	if(is_numeric($action)){
		//
		include TS_CLASS . "c.borradores.php";
		$tsDrafts = new tsDrafts();
		$tsBorrador = $tsDrafts->getDraft();
		$smarty->assign("tsDraft", $tsBorrador);
		//
	} elseif($action == 'editar') {
		// GUARDAR
		if(!empty($_POST['titulo'])){
		  $post_save = $tsAgregar->savePost();
			if($post_save == 1) {
				$cid = (int)$_POST['categoria'];
				$tsCat = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT c.c_seo FROM @posts_categorias AS c WHERE c.cid = $cid LIMIT 1"));
				//
				$post_url = $tsCore->createLink('post', [
					'c_seo' => $tsCat['c_seo'],
					'post_id' => (int)$_GET['pid'],
					'post_title' => $_POST['titulo']
				]);
				// NOS VAMOS AL POST
				$tsCore->redirectTo($post_url);
			} else {
            $tsPage = 'aviso';
            $smarty->assign("tsAviso", [
            	'titulo' => 'Oops!', 
            	'mensaje' => $post_save, 
            	'but' => 'Volver', 
            	'link' => 'javascript:history.go(-1)'
            ]);
			}
		// EDITAR
		} else {
        	$draft = $tsAgregar->getEditPost();
        	if(!is_array($draft)){
        	   $tsPage = 'aviso';
        	   $smarty->assign("tsAviso", [
        	   	'titulo' => 'Opps...', 
        	   	'mensaje' => $draft, 
        	   	'but' => 'Ir a pagina principal', 
        	   	'link' => $tsCore->settings['url']
        	   ]);
        	} else $smarty->assign("tsDraft", $draft);
		}
		//
		$smarty->assign("tsAction",$_GET['action']);
		$smarty->assign("tsPid",$_GET['pid']);
		
	}elseif($_POST['titulo']){
		//
		$tsPost = $tsAgregar->newPost();
		//
		$tsPage = 'aviso';
		$tsAjax = 0;
		if($tsPost > 0) {
			$tsCat = (int)$_POST['categoria'];
			$query = db_exec([__FILE__, __LINE__], 'query', "SELECT c.c_seo FROM @posts_categorias AS c WHERE c.cid = $tsCat LIMIT 1");
			$tsCat = db_exec('fetch_assoc', $query);
			
			$post_url = $tsCore->createLink('post', [
				'c_seo' => $tsCat['c_seo'],
				'post_id' => (int)$tsPost,
				'post_title' => $_POST['titulo']
			]);
			// NOS VAMOS AL POST
			$tsCore->redirectTo($post_url);
		} elseif($tsPost == -1){
			$smarty->assign("tsAviso",array('titulo' => 'Anti Flood', 'mensaje' => "No puedes realizar tantas acciones en tan poco tiempo. Vuelve a intentarlo en unos instantes.", 'but' => 'Volver', 'link' => "javascript:history.go(-1)"));
		} else {
			$smarty->assign("tsAviso",array('titulo' => 'Oops!', 'mensaje' => "Ha ocurrido un error intentalo m&aacute;s tarde.<br><b>Error</b>: ".$tsPost, 'but' => 'Volver', 'link' => 'javascript:history.go(-1)'));
		}
	}
	
/**********************************\

* (AGREGAR DATOS GENERADOS | SMARTY) *

\*********************************/
	}

if(empty($tsAjax)) {	// SI LA PETICION SE HIZO POR AJAX DETENER EL SCRIPT Y NO MOSTRAR PLANTILLA, SI NO ENTONCES MOSTRARLA.

	$smarty->assign("tsTitle",$tsTitle);	// AGREGAR EL TITULO DE LA PAGINA ACTUAL
	$smarty->assign("tsSubmenu","agregar");

	/*++++++++ = ++++++++*/
	include TS_ROOT . 'footer.php';
	/*++++++++ = ++++++++*/
}
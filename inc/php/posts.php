<?php 
/**
 * Controlador
 *
 * @name    posts.php
 * @author  Miguel92 & PHPost.es
*/

/*
 * -------------------------------------------------------------------
 *  Definiendo variables por defecto
 * -------------------------------------------------------------------
*/

$tsPage = "posts";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

$tsLevel = 0;		// NIVEL DE ACCESO A ESTA PAGINA

$tsAjax = empty($_GET['ajax']) ? 0 : 1; // LA RESPUESTA SERA AJAX?

$tsContinue = true;	// CONTINUAR EL SCRIPT

$tsTitle = $tsCore->settings['titulo']; 	// TITULO DE LA PAGINA ACTUAL

/*
 * -------------------------------------------------------------------
 *  Validando nivel de acceso
 * -------------------------------------------------------------------
*/

// Nivel y permisos de acceso
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) {
	$tsPage = 'aviso';
	$tsAjax = 0;
	$smarty->assign("tsAviso", $tsLevelMsg);
	//
	$tsContinue = false;
}
//
if($tsContinue) {

/*
 * -------------------------------------------------------------------
 *  Estableciendo variables y archivos 
 * -------------------------------------------------------------------
 */
	// Afiliados
	include TS_CLASS . "c.afiliado.php";
	$tsAfiliado = new tsAfiliado();
		 
	// Referido?
	if(!empty($_GET['ref'])) $tsAfiliado->urlIn();
	 
	// Posts Class
	include TS_CLASS . "c.posts.php";
	$tsPosts = new tsPosts();

	// Comentarios Class
	include TS_CLASS . "c.comentarios.php";
	$tsComentarios = new tsComentarios();
	 
	// Category
	$category = htmlentities($_GET['cat'] ?? '');
	 
	// Post anterior/siguiente
	if(in_array($_GET['action'], ['next', 'prev', 'fortuitae'])) $tsPosts->setNP();

/*
 * -------------------------------------------------------------------
 *  Tareas principales
 * -------------------------------------------------------------------
 */
	if(!empty($_GET['post_id'])) {
		  
		// DATOS DEL POST
		$tsPost = $tsPosts->getPost();
		//
		if($tsPost['post_id'] > 0) {
			// TITULO NUEVO
			$tsTitle = $tsPost['post_title'].' - '.$tsTitle;
			// ASIGNAMOS A LA PLANTILLA
			$smarty->assign("tsPost", $tsPost);
			// DATOS DEL AUTOR
			$smarty->assign("tsAutor", $tsPosts->getAutor($tsPost['post_user']));						
			// DATOS DEL RANGO DEL PUTEADOR						
			$smarty->assign("tsPunteador", $tsPosts->getPunteador());
			// RELACIONADOS
			$smarty->assign("tsRelated", $tsPosts->getRelated($tsPost['post_tags']));
			$smarty->assign("tsPostAutor", $tsPosts->getPostAutor($tsPost['post_user']));
			// COMENTARIOS
			$tsComments = $tsComentarios->getComentarios($tsPost['post_id']);
			$smarty->assign("tsComments", [
				'num' => $tsComments['num'], 
				'data' => $tsComments['data']
			]);
			// PAGINAS
			$tsPages = $tsCore->getPages((int)$tsPost['post_comments'], (int)$tsCore->settings['c_max_com']);
			$tsPages['post_id'] = $tsPost['post_id'];
			$tsPages['autor'] = $tsPost['post_user'];
			$smarty->assign("tsAnterior", $tsPosts->getTitles('prev'));
         $smarty->assign("tsSiguente", $tsPosts->getTitles('next'));
			//
			$smarty->assign("tsPages", $tsPages);
			require TS_EXTRA . 'datos.php';
			$smarty->assign("tsReactions", $reacciones);

		} else {
			//
			$tsPage = "post.aviso";
			$tsAjax = 0;
			$smarty->assign("tsAviso", $tsPost);
			if($tsPost[0] === 'privado') {
				//
				$title = str_replace("-",",", $tsCore->setSecure($_GET['title']));
				$title = explode(",", $title);
				// RELACIONADOS
				$tsRelated = $tsPosts->getRelated($title);
				$smarty->assign("tsRelated", $tsRelated);
				$tsTitle = $tsPost[1] .' - ' . $tsTitle;
				$tsPage = "post.privado";
			}
			$smarty->assign("tsType", 'post');

		}
	} else {
		// PAGINA
		$tsPage = "home";
		$tsTitle = $tsTitle.' - '.$tsCore->settings['slogan']; 	// TITULO DE LA PAGINA ACTUAL
		// CLASE TOPS
		include TS_CLASS."c.tops.php" ;
		$tsTops = new tsTops();
		// ULTIMOS POSTS
		$tsLastPosts = $tsPosts->getLastPosts($category);
		$smarty->assign("tsPosts", $tsLastPosts['data']);
		$smarty->assign("tsPages", $tsLastPosts['pages']);
	
		// ULTIMOS POSTS FIJOS
		$smarty->assign("tsPostsStickys", $tsPosts->getLastPostsStickys());
		// CAT
		$smarty->assign("tsCat", $category);
		$smarty->assign("tsStats", $tsTops->getStats());
		// ULTIMOS COMENTARIOS
		$smarty->assign("tsComments", $tsComentarios->getLastComentarios());
		// TOP POSTS
		$smarty->assign("tsTopPosts", $tsTops->getHomeTopPosts()['historico']);
		// TOP USERS
		$smarty->assign("tsTopUsers", $tsTops->getHomeTopUsers()['historico']);
		// TITULO
		if(!empty($category)) {
			$catData = $tsPosts->getCatData($category);
			$tsTitle = $tsCore->settings['titulo'].' - '.$catData['c_nombre'];
			$smarty->assign("tsCatData", $catData);
		}
		  
		// AFILIADOS
		$smarty->assign("tsAfiliados", $tsAfiliado->getAfiliados());
		// DO <= PARA EL MENU
		$smarty->assign("tsDo", $_GET['do']);

	}

}
/*
 * -------------------------------------------------------------------
 *  Incluir plantilla
 * -------------------------------------------------------------------
 */

if(empty($tsAjax)) 
{
	 // Asignamos título
	$smarty->assign("tsTitle", $tsTitle);
	 // Incluir footer
	include(TS_ROOT . "/footer.php");
}
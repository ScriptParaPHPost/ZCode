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

use app\models\{
	Afiliado,
	Comentarios,
	Foro,
	Home,
	Posts,
	Tops
};

$tsPage = "posts";

$tsLevel = 0;

$tsAjax = empty($_GET['ajax']) ? 0 : 1;

$tsContinue = true;

$tsTitle = $tsCore->settings['titulo'];

// Nivel y permisos de acceso
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if(!$tsLevelMsg) {
	$tsPage = 'aviso';
	$tsAjax = 0;
	$smarty->assign("tsAviso", $tsLevelMsg);
	//
	$tsContinue = false;
}

if($tsContinue) {
	
	// Afiliados
	$tsAfiliado = new Afiliado;
		 
	// Referido?
	if(!empty($_GET['ref'])) $tsAfiliado->urlIn();

	// Posts Class
	$tsPosts = new Posts;
	
	// Comentarios Class
	$tsComentarios = new Comentarios;
	 	
	// Category
	$category = htmlentities($_GET['cat'] ?? ($_GET["category"] ?? ''));
	 
	// Post anterior/siguiente
	if(isset($_GET['action'])) {
		if(in_array($_GET['action'], ['next', 'prev', 'fortuitae'])) $tsPosts->setNP();
	}

	if(!empty($_GET['post_id'])) {
		  
		// DATOS DEL POST
		$tsPost = $tsPosts->getPost();
		//
		if(isset($tsPost['post_id']) && $tsPost['post_id'] > 0) {
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
			require TS_JUNK . 'datos.php';
			$smarty->assign("tsReactions", $reacciones);

		} else {
			//
			$tsAjax = 0;
			$smarty->assign("tsAviso", $tsPost);
			// RELACIONADOS
			$tsRelated = $tsPosts->getRelated();
			$smarty->assign("tsRelated", $tsRelated);
			$tsTitle = $tsPost[1] .' - ' . $tsTitle;
			$tsPage = "post." . ($tsPost[0] === 'privado' ? 'privado' : 'aviso');
		
			$smarty->assign("tsType", 'post');

		}
	} else {
		// PAGINA
		$tsPage = "home";
		$tsTitle = $tsTitle.' - '.$tsCore->settings['slogan']; 	// TITULO DE LA PAGINA ACTUAL
		// CLASE TOPS
		$tsHome = new Home();
		$tsTops = new Tops();
		
		// CAT
		$smarty->assign("tsCat", $category);
		// TITULO
		if(!empty($category)) {
			$catData = $tsHome->getCatData($category);
			$tsTitle = $tsCore->settings['titulo'].' - '.$catData['c_nombre'];
			$smarty->assign("tsCatData", $catData);
		}
		if((int)$tsCore->settings['c_allow_foro'] === 0 || !empty($category)) {
			// ULTIMOS POSTS
			$tsLastPosts = $tsHome->getLastPosts($category);
			$smarty->assign("tsPosts", $tsLastPosts['data']);
			$smarty->assign("tsPages", $tsLastPosts['pages']);
			// ULTIMOS POSTS FIJOS
			$smarty->assign("tsPostsStickys", $tsHome->getLastPostsStickys());
			// AFILIADOS
			$smarty->assign("tsAfiliados", $tsAfiliado->getAfiliados());
		}
		if((int)$tsCore->settings['c_allow_foro'] === 1) {
			$tsForo = new Foro;
			$smarty->assign("tsForos", $tsForo->getForoPosts());
			#var_dump($tsForo->getForoPosts());
		}
		$smarty->assign("tsStats", $tsTops->getStats());
		// ULTIMOS COMENTARIOS
		$smarty->assign("tsComments", $tsComentarios->getLastComentarios());
		// TOP POSTS
		$smarty->assign("tsTopPosts", $tsTops->getHomeTopPosts()['historico']);
		// TOP USERS
		$smarty->assign("tsTopUsers", $tsTops->getHomeTopUsers()['historico']);
	}
}

if(empty($tsAjax)) {
	
	$smarty->assign("tsTitle", $tsTitle);
	
	include BASEPATH . "footer.php";

}
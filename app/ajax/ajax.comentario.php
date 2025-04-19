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

use app\models\{Comentarios,Fotos};

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCI�N
$files = [
	'comentario-preview' => ['n' => 2, 'p' => 'preview'],
	'comentario-agregar' => ['n' => 2, 'p' => 'preview'],
	'comentario-editar' => ['n' => 2, 'p' => ''],
	'comentario-borrar' => ['n' => 2, 'p' => ''],
	'comentario-ocultar' => ['n' => 2, 'p' => ''],
	'comentario-votar' => ['n' => 2, 'p' => ''],
	'comentario-reaccion' => ['n' => 2, 'p' => ''],
	'comentario-ajax' => ['n' => 0, 'p' => 'ajax'],
	'comentario-pages' => ['n' => 0, 'p' => 'pages'],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.comentario.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1) { 
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
}
//
$do = $_GET['do'] ?? '';
$type = $tsCore->setSecure($_POST['type'] ?? 'posts');

// CLASE
$tsComentarios = new Comentarios();

if($do === 'fotos' OR $type === 'fotos'){
	$tsFotos = new Fotos();
}

// CODIGO
switch($action){
	case 'comentario-preview':
		$comentario = $tsCore->setSecure($_POST['comentario']);
		$comentario = substr($comentario,0,1500);
		// COMENTARIO VACIO?
		$tsText = preg_replace('# +#',"",$comentario);
		if(empty($tsText)) die('0: El campo <b>Comentario</b> es requerido para esta operaci&oacute;n');
			//
		$auser = $_POST['auser'];
		$preview = array(0,$tsCore->parseBBCode($comentario),'',time(),$auser, $comentario, $_SERVER['REMOTE_ADDR']);
		$smarty->assign("tsComment",$preview);
		$smarty->assign("tsType",$_GET['type']);
	break;
	case 'comentario-agregar':
		if($type === 'posts') {
			$tsComment = $tsComentarios->newComentario();
			$smarty->assign("tsType", 'new');
			//
			if(is_array($tsComment)) $smarty->assign("tsComment",$tsComment);
			else die($tsComment);
		} elseif($type === 'fotos') {
			//
			$tsComment = $tsFotos->newComentario();
			if(is_array($tsComment)) $smarty->assign("tsComment", $tsComment);
			else die($tsComment);
			// NUEVA PLANTILLA
			$tsPage = 'php_files/p.comentario.fotos';
		}
	break;
	case 'comentario-editar':
		echo $tsComentarios->editComentario();
	break;
	case 'comentario-borrar':
		echo empty($do) ? $tsComentarios->delComentario() : $tsFotos->delComentario();
	break;
	case 'comentario-ocultar':
		echo $tsComentarios->OcultarComentario();
	break;
	case 'comentario-votar':
	case 'comentario-reaccion':
		echo empty($do) ? $tsComentarios->reaccionarComentario() : $tsFotos->votarFoto();
	break;
	case 'comentario-ajax':
		// COMENTARIOS
		$tsPost = $tsCore->setSecure($_POST['postid']);
		$tsAutor = $tsCore->setSecure($_POST['autor']);
		$tsComments = $tsComentarios->getComentarios($tsPost);
		$tsComments = [
			'num' => $tsComments['num'], 
			'data' => $tsComments['data'], 
			'block' => $tsComments['block'], 
			'autor' => $tsAutor
		];
		$smarty->assign("tsComments", $tsComments);	
		$smarty->assign("tsPost", ['postid' => $tsPost, 'autor' => $tsAutor]);
	break;
	case 'comentario-pages':
		$_GET['ts'] = true;
		$total = $tsCore->setSecure($_POST['total']);
		$tsPages = $tsCore->getPages($total, $tsCore->settings['c_max_com']);
		$tsPages['post_id'] = $tsCore->setSecure($_POST['postid']);
		$tsPages['autor'] = $tsCore->setSecure($_POST['autor']);
		//
		$smarty->assign("tsPages",$tsPages);
	break;
}
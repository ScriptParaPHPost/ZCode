<?php 

if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * Controlador AJAX
 *
 * @name    ajax.emojis.php
 * @author  Miguel92
*/


$files = [
   'emojis' => ['n' => 2, 'p' => ''],
];

// REDEFINIR VARIABLES
$tsPage = 'ajax/p.emojis.'.$files[$action]['p'];
$tsLevel = $files[$action]['n'];
$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1):
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
endif;

// CODIGO
switch($action){
	case 'emojis':
		$file = json_decode(file_get_contents(TS_ASSETS . 'icons/emojis/emoji.json'), true);
		$agregar = [];
		foreach ($file as $name => $emoji) {
		    if ($emoji['category'] == 'people' && (int)$emoji['emoji_order'] <= 95) {
		        $image = $tsCore->settings['assets'] . "/icons/emojis/small/$name.png";
		        $agregar[$name] = [
		            'bbcode' => $emoji['shortname'],
		            'title' => $emoji['name'],
		            'img' => "<img title=\"{$emoji['name']}\" class=\"emoji emoji-people\" src=\"$image\"/>"
		        ];
		    }
		}
		echo json_encode($agregar, JSON_FORCE_OBJECT);
	break;
}
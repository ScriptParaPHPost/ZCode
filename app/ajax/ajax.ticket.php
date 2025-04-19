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

use app\models\Ticket;

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

// NIVELES DE ACCESO Y PLANTILLAS DE CADA ACCI�N
$files = [
	'ticket-last-open' => ['n' => 4, 'p' => 'last-open'],
	'ticket-list' => ['n' => 4, 'p' => 'list'],
];

// REDEFINIR VARIABLES
$tsPage = 'php_files/p.ticket.'.$files[$action]['p'];

$tsLevel = $files[$action]['n'];

$tsAjax = empty($files[$action]['p']) ? 1 : 0;

// DEPENDE EL NIVEL
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);

if(!$tsLevelMsg) { 
	echo '0: '.$tsLevelMsg['mensaje']; 
	die();
}
//
$do = $_GET['do'] ?? '';

// CLASE
$tsTicket = new Ticket();

// CODIGO
switch($action){
	case 'ticket-last-open':
		$smarty->assign("tsTicketOpen", $tsTicket->getTicketsOpenHome());
	break;
	case 'ticket-list':
		$getTickets = $tsTicket->getTickets();
	
		$smarty->assign('tsTicketList', $getTickets['data']);
		$smarty->assign('tsTicketPages', $getTickets['pages']);

	break;
}
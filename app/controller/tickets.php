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

$tsPage = "tickets";

$tsLevel = 2;

$tsAjax = empty($_GET['ajax']) ? 0 : 1;
	
$tsContinue = true;
	
include realpath('../../') . DIRECTORY_SEPARATOR . "header.php";

$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['slogan'];

// VERIFICAMOS EL NIVEL DE ACCSESO ANTES CONFIGURADO
$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
if($tsLevelMsg != 1){	
	$tsPage = 'aviso';
	$tsAjax = 0;
	$smarty->assign("tsAviso",$tsLevelMsg);
	//
	$tsContinue = false;
}

if($tsContinue) {

/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	// CLASE TOPS
	$tsTicket = new Ticket();

	$action = htmlspecialchars($_GET['action'] ?? '');

	if((int)$tsCore->settings['c_allow_ticket'] === 0) {
		header("Location: {$tsCore->settings['url']}/");
		die;
	}

	if((!$tsUser->is_admod || $tsUser->permisos['moat']) AND empty($action)) {
		header("Location: {$tsCore->settings['url']}/tickets/mis-tickets/");
		die;
	}
	$filt = [];
	foreach($tsTicket->getTypeStatus() as $k => $val) array_push($filt, $val['status_slug']);
	if(isset($_GET['action']) AND in_array($_GET['action'], $filt)) {
		$action = 'filtro';
	}
	switch ($action) {
		case '':
		case 'filtro':
			if(empty($action)) {
				$action = 'inicio';
				$smarty->assign('tsPageNow', $_GET['s']);
				$smarty->assign('tsTicketTitle', "Todos los tickets");
			} else {
				$smarty->assign('tsFilter', $_GET['action']);
				$smarty->assign('tsTicketTitle', "Tickets en {$tsTicket->getTypeStatus($_GET['action'])}");
			}

			$getTickets = $tsTicket->getTickets();
			$smarty->assign('tsTicketFilter', $tsTicket->getTypeStatus());
			$smarty->assign('tsTicketList', $getTickets['data']);
			$smarty->assign('tsTicketPages', $getTickets['pages']);
			
		break;
		case 'ver-ticket':
			if((int)$_GET['tid'] AND is_numeric($_GET['tid'])) {
				$smarty->assign('tsTicket', $tsTicket->getTicket());
			}
		break;
		case 'mis-tickets':
			$smarty->assign('tsTicketTitle', "Mis tickets");
			$smarty->assign('tsTicketList', $tsTicket->getTicketsOpenHome('admin'));
		break;
		default:
			// code...
		break;
	}

	$smarty->assign('tsAction', $action);
}

if(empty($tsAjax)) {
	
	$smarty->assign("tsTitle", $tsTitle);
	
	include BASEPATH . 'footer.php';
	
}
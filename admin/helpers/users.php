<?php 

$tsTitle = 'Todos los Usuarios';
$user_id = (int)$_GET['uid'];
if(empty($act)) {
	$smarty->assign("tsMembers",$tsAdmin->getUsuarios());

} elseif($act === 'verificar') {
  	if(!$tsAdmin->setUsuarioVerificado())  $smarty->assign("tsError", $update); 

} elseif($act === 'show') {
   $do = (int)$_GET['t'];
   // HACER
   switch($do){
		case 5:
		case 6:
		case 7:
		case 8:
			if($do === 6) {
				require TS_ZCODE . 'ContentUserInfo.php';
				$smarty->assign("contentUser", $contentUser);
			}
  	      if(!empty($_POST['save'])) {
  	         $both = ($do === 5) ? $tsAdmin->setUserPrivacidad($user_id) : ($do === 7 ? $tsAdmin->setUserRango($user_id) : ($do === 8 ? $tsAdmin->setUserFirma($user_id) : $tsAdmin->deleteContent($user_id)));
  	         if($both === 'OK') $tsCore->redireccionar('admin', $action, "act=show&uid=$user_id&save=true");
            else $smarty->assign("tsError", $both);
         }
         if($do === 7) {
         	$smarty->assign("tsUserR", $tsAdmin->getUserRango($user_id));
         } elseif($do === 8) {
         	$smarty->assign("tsUserF", $tsAdmin->getUserData());
         } else {
         	include_once TS_ZCODE . "datos.php";
         	$smarty->assign("tsPerfil", $tsAdmin->getUserPrivacidad());
				$smarty->assign("tsPrivacidad", $tsPrivacidad);
         }
      break;
      default:
         if(!empty($_POST['save'])){
  	       	$update = $tsAdmin->setUserData($user_id);
  	       	if($update === 'OK') $tsCore->redirectTo($tsCore->settings['url'].'/admin/users?act=show&uid='.$user_id.'&save=true');
            else $smarty->assign("tsError",$update);
         }
       	$smarty->assign("tsUserD",$tsAdmin->getUserData());
      break;
   }
   // TIPO
   $smarty->assign("tsType",$_GET['t']);
   $smarty->assign("tsUserID",$user_id);
   $smarty->assign("tsUsername",$tsUser->getUserName($user_id));
}
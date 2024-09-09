<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Controlador AJAX
 *
 * @name    ajax.recover.php
 * @author  ZCode | PHPost
*/

$tsLevel = 1; // solo visitantes

$tsLevelMsg = $tsCore->setLevel($tsLevel, true);

if($tsLevelMsg != 1) die('0: '.$tsLevelMsg);

$email = $tsCore->setSecure($_POST['r_email']);

$USERINFO = db_exec([__FILE__, __LINE__], 'query', "SELECT user_id, user_name, user_registro, user_activo FROM @miembros WHERE user_email = '$email'");

if(!db_exec('num_rows', $USERINFO)) die('0: <div class="dialog_box">El email no se encuentra registrado.</div>');

$tsData = db_exec('fetch_assoc', $USERINFO);
	
include_once TS_MODELS . 'c.emails.php';

switch($action){
	case 'recover-pass':
		$tsEmail = new tsEmail('nope', 'chuck testa!'); // wtf
		$key = md5(sha1(uniqid(time().$email.microtime(true), true)));
		db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @contacts (user_id, user_email, `time`, `type`, `hash`) VALUES (\''.$tsData['user_id'].'\', \''.$email.'\', \''.time().'\', \'1\', \''.$key.'\')');

		$encode = $tsCore->setSecure($email);
		$body = "Recuperar contrase&ntilde;a en <strong>{$tsCore->settings['titulo']}</strong><br><br>Hola {$tsData['user_name']}:<br />
				La verificaci&oacute;n es usada para asegurar que s&oacute;lo usted tenga acceso a 
				su cuenta de {$tsCore->settings['titulo']} y que, si alguna vez olvida su contrase&ntilde;a, tengamos una forma de generarle una nueva.<br><br>Para recuperar su contrase&ntilde;a, acceda a <a href=\"{$tsCore->settings['url']}/password/$key/1/$encode\">este enlace</a></strong>";
		
		// <--
		$tsEmail->emailTemplate = 'recover';
		$tsEmail->emailTo = $email;
		$tsEmail->emailSubject = 'Recuperar acceso';
		$tsEmail->emailBody = $body;
		$tsEmail->sendEmail() or die('0: Hubo un error al intentar procesar lo solicitado');
		die('1: <div class="dialog_box">Las intrucciones para recuperar su contrase&ntilde;a de <strong>'.$tsCore->settings['titulo'].'</strong> a <strong>'.$email.'</strong>, si no aparece el e-mail en su bandeja de entrar, revise en correo no deseado porque puede haberse filtrado..</div>');
			// -->
	break;
	case 'recover-validation':

		if($tsData['user_activo'] == 1) die('0: La cuenta ya se encuentra activada');

     		$tsEmail = new tsEmail($tsData, 'signup');
			$key = md5(sha1(uniqid(time().$email.microtime(true), true)));
			db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO @contacts (user_id, user_email, `time`, `type`, `hash`) VALUES (\''.$tsData['user_id'].'\', \''.$email.'\', \''.time().'\', \'2\', \''.$key.'\')');

			$encode = $tsCore->setSecure($email);
			$body = "Activaci&oacute;n de cuentas en <strong>{$tsCore->settings['titulo']}</strong><br><br>Hola {$tsData['user_name']}:<br>Le enviamos este email para confirmar el registro de su cuenta en {$tsCore->settings['titulo']}.<br><br>Para terminar el registro y poder acceder a la comunidad, acceda a <a href=\"{$tsCore->settings['url']}/validar/$key/2/$encode\">este enlace</a>";
			
			// <--
			$tsEmail->emailTemplate = 'recover';
			$tsEmail->emailTo = $email;
			$tsEmail->emailBody = $body;
			$tsEmail->emailSubject = "{$tsData['user_name']}, active su cuenta ahora";
			$tsEmail->emailHeaders = $tsEmail->setEmailHeaders();
			$tsEmail->sendEmail($from, $to, $subject, $body)  or die('0: Hubo un error al intentar procesar lo solicitado');
			die('1: <div class="box_cuerpo" style="padding: 12px 20px; border-top:1px solid #CCC">Hemos enviado un correo a <strong>'.$email.'</strong> con los &uacute;ltimos pasos para finalizar con el registro.<br><br>Si en los pr&oacute;ximos minutos no lo encuentras en tu bandeja de entrada, por favor, revisa tu carpeta de correo no deseado, es posible que se haya filtrado.<br><br>&iexcl;Muchas gracias!</div>');
			// -->
			break;
		default:
			die('0: Este archivo no existe.');
			break;
	}
<?php 

$_SESSION['LICENSE'] = ($FN->setInput('agree', 'string', INPUT_POST) === 'true');

# Obtenemos la licencia.
$LICENSE = $FN->get_data_file(__DIR__ . '/../../LICENSE');

if($FN->license() && $FN->request()) {
	$FN->save([
		'__status__' 		 => $FN->status_install(), 
		'__mode__' 			 => $FN->isLocalhost(), 
		'__session_name__' => $FN->key_generator('session'), 
		'__script__' 		 => 'WkNvZGVVcGdyYWRl',
		'__key__' 			 => $FN->key_generator('verify'),
		'__pin__' 			 => $FN->key_generator('pin', 20),
		'__license__' 		 => $FN->generate_key()
	]);
	$continue = true;
	header("Location: ?action=requisitos");
}
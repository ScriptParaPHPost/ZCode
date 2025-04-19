<?php 

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package     ZCode
 * @author      Miguel92
 * @copyright   2024 - 2025
 * @version     3.1.18
 * @link        https://zcodev.alwaysdata.net/ (DEMO)
 * @link        https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link        https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

function uploadImageToImgur($imagePath) {
	$server  = 'https://api.imgur.com/3/image';
	$headers = [
		'Authorization: Client-ID b2fddcb704b44a5'
	];

	// Asegurarse de que el archivo es una imagen válida.
	if (getimagesize($imagePath) === false) {
		return json_encode(['status' => 0, 'msg' => 'El archivo no es una imagen válida.']);
	}

	$image = file_get_contents($imagePath);
	if ($image === false) {
		return json_encode(['status' => 0, 'msg' => 'No se pudo leer el archivo de imagen.']);
	}

	$pvars = [
	 	'image' => base64_encode($image)
	];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $server);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $pvars);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);  // Seguridad SSL activada

	$result = curl_exec($ch);

	// Manejo de errores de curl.
	if (curl_errno($ch)) {
		return json_encode(['status' => 0, 'msg' => 'Error en la conexión a la API de Imgur: ' . curl_error($ch)]);
	}

	curl_close($ch);

	// Decodificación de la respuesta JSON de Imgur.
	$data = json_decode($result, true);
	if (isset($data['data']['link'])) {
		return json_encode([
			'status' => 1,
			'msg' => 'OK',
			'image_link' => $data['data']['link'],
			'thumb_link' => $data['data']['link'] // Puedes cambiarlo si deseas la URL del thumbnail.
		]);
	}

	return json_encode(['status' => 0, 'msg' => 'No se pudo obtener el enlace de la imagen.']);
}

if (isset($_FILES['img'])) {
	$isIframe = isset($_POST["iframe"]) && $_POST["iframe"] == 'true';
	$idarea   = $_POST["idarea"];
	
	// Llamada a la función optimizada para subir la imagen.
	$response = uploadImageToImgur($_FILES['img']['tmp_name']);
	$data = json_decode($response, true);

	// Respuesta según el contexto (iframe o no).
	if ($data['status'] === 1) {
		if ($isIframe) {
			echo '<html><body>OK<script>window.parent.$("#' . $idarea . '").insertImage("' . $data['image_link'] . '","' . $data['image_link'] . '").closeModal().updateUI();</script></body></html>';
		} else {
			header("Content-type: application/json");
			echo json_encode([
				'status' => 1,
				'msg' => 'OK',
				'image_link' => $data['image_link'],
				'thumb_link' => $data['thumb_link']
			]);
		}
	} else {
		header("Content-type: application/json");
		echo $response;  // Se devuelve el error de la API o el error de conexión.
	}
} else {
	header("Content-type: application/json");
	echo json_encode(['status' => 0, 'msg' => 'No se ha subido ninguna imagen.']);
}
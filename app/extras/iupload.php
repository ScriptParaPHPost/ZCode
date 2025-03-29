<?php 

if ( ! defined('ZCODE2')) exit('No se permite el acceso directo al script');

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

function upload($imagePath) {
   $imageData = base64_encode(file_get_contents($imagePath));
   $ch = curl_init('https://api.imgur.com/3/image.json');

   curl_setopt_array($ch, [
      CURLOPT_TIMEOUT => 30,
      CURLOPT_POST => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => ['Authorization: Client-ID b2fddcb704b44a5'],
      CURLOPT_POSTFIELDS => ['image' => $imageData],
      CURLOPT_SSL_VERIFYPEER => true
   ]);

   $response = curl_exec($ch);
   curl_close($ch);

   return $response ? json_decode($response, true) : null;
}

function sendJsonResponse(array $data) {
   header('Content-Type: application/json');
   echo json_encode($data);
   exit;
}

if (empty($_FILES['img']['tmp_name'])) {
   sendJsonResponse(['status' => 0, 'msg' => 'No image uploaded']);
}

$isIframe = !empty($_POST['iframe']);
$idArea = htmlspecialchars($_POST['idarea']) ?? '';
$uploadResponse = upload($_FILES['img']['tmp_name']);

if (!$uploadResponse || !isset($uploadResponse['data']['link'])) {
   sendJsonResponse(['status' => 0, 'msg' => 'Upload failed']);
}

$imgUrl = $uploadResponse['data']['link'];

if (!$isIframe) {
   sendJsonResponse([
      'status' => 1,
      'msg' => 'OK',
      'image_link' => $imgUrl,
      'thumb_link' => $imgUrl
   ]);
}

// Iframe response
?>
<html>
<body>
	OK
	<script>
   	<?php 
   		echo "window.parent.$(\"#$idArea\").insertImage(\"$imgUrl\", \"$imgUrl\").closeModal().updateUI();";
   	?>
	</script>
</body>
</html>
<?php 

/**
 * Nueva versión de este código
*/
class LimpiarSolicitud extends tsCore {
	private $solicitudes;
   public function __construct() {}
   private function mQSybase() {
   	$get = 'magic_quotes_sybase';
   	return @ini_get($get) || strtolower(@ini_get($get)) == 'on';
   }
   private function teclasNumericas() {
		foreach (array_merge(array_keys($_POST), array_keys($_GET), array_keys($_FILES)) as $key) {
			if (is_numeric($key)) die('Las claves de solicitud numérica no son válidas.');
		}
   }
   private function cookies_key() {
		foreach ($_COOKIE as $key => $value) {
			if (is_numeric($key)) unset($_COOKIE[$key]);
		}
   }
   public function limpiar() {
   	// Página actual
   	$tsPage = $GLOBALS['_GET']['do'];
   	// Qué función usar para revertir las comillas mágicas
		$removeMQF = ($this->mQSybase() ? 'unescapestring' : 'stripslashes') . '__recursive';
		// Ahorre algo de memoria ... (ya que no los usamos de todos modos).
		unset($GLOBALS['HTTP_POST_VARS'], $GLOBALS['HTTP_POST_VARS']);
		// Estas teclas no deben configurarse... nunca.
		if (isset($_REQUEST['GLOBALS']) || isset($_COOKIE['GLOBALS'])) die('Variable de solicitud no válida.');
		// Lo mismo ocurre con las teclas numéricas.
		$this->teclasNumericas();
		// Las claves numéricas en las cookies son un problema menor. Solo desarma esos.
		$this->cookies_key();
		// Obtenga la cadena de consulta correcta. Puede estar en una variable de entorno...
		if (!isset($_SERVER['QUERY_STRING'])) $_SERVER['QUERY_STRING'] = getenv('QUERY_STRING');
		// Parece que pegar una URL después de la cadena de consulta es muy común!
		if (strpos($_SERVER['QUERY_STRING'], 'http') === 0) {
		   http_response_code(400); 
		   die;
		}
		// Si las comillas mágicas están activadas, tenemos algo de trabajo...
		if (function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc() != 0) {
			$_ENV = $removeMQF($_ENV);
			$_POST = $removeMQF($_POST);
			$_COOKIE = $removeMQF($_COOKIE);
			foreach ($_FILES as $k => $dummy) {
				if (isset($_FILES[$k]['name'])) $_FILES[$k]['name'] = $removeMQF($_FILES[$k]['name']);
			}
		}
		// Agregar entidades a GET. Esto es un poco como las barras en todo lo demás.
		// htmlspecialchars__recursive
		$_GET = $this->htmlspecialchars__recursive($_GET);
	   $_POST = $this->htmlspecialchars__recursive($_POST);
	   $_COOKIE = $this->htmlspecialchars__recursive($_COOKIE);
	   // No dependamos de la configuración ini ... ¿por qué incluso tener COOKIE allí, de todos modos?
		$_REQUEST = $_POST + $_GET;
		// Compruebe si la solicitud proviene de este sitio
   	$referer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "";
		$host = isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : "";
		$IsMySite = strpos(preg_replace("/https?:\/\/|www\./", "", $referer), preg_replace("/https?:\/\/|www\./", "", $host)) === 0;
   	$findPage = ['admin', 'moderacion', 'cuenta'];
   	if((!empty($_SERVER["HTTP_REFERER"]) && (in_array($tsPage, $findPage) || $_SERVER['QUERY_STRING'] == 'action=login-salir') && !$IsMySite) || $_SERVER["REQUEST_METHOD"] === "POST" && !$IsMySite) die("Invalid request");
   }
	// Agrega entidades html a la matriz/variable.
	// Utiliza dos guiones bajos para evitar la sobrecarga.
	private function htmlspecialchars__recursive($var, $level = 0) {
		if (!is_array($var)) return htmlspecialchars($var, ENT_QUOTES);
		// Agregue los htmlspecialchars a cada elemento.
		foreach ($var as $k => $v) $var[$k] = $level > 25 ? null : $this->htmlspecialchars__recursive($v, $level + 1);
		return $var;
	}
	// Elimina el escape de cualquier matriz o variable. Dos guiones bajos por la razón normal
	private function unescapestring__recursive($var) {
		if (!is_array($var)) return stripslashes($var);
		// Vuelva a indexar la matriz sin barras, esta vez.
		$new_var = [];
		// Quita las barras de cada elemento.
		foreach ($var as $k => $v) $new_var[stripslashes($k)] = $this->unescapestring__recursive($v);
		return $new_var;
	}
	// Eliminar barras recursivamente...
	private function stripslashes__recursive($var, $level = 0) {
		if (!is_array($var)) return stripslashes($var);
		// Vuelva a indexar la matriz sin barras, esta vez.
		$new_var = [];
		// Quita las barras de cada elemento.
		foreach ($var as $k => $v) $new_var[stripslashes($k)] = $level > 25 ? null : $this->stripslashes__recursive($v, $level + 1);
		return $new_var;
	}
}
$cleanRequest = new LimpiarSolicitud;
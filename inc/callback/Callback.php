<?php 

/**
 * @name Callback.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v1.8.11
 * @description Para realizar el logueo o registro de usuarios
**/

require realpath('../../') . DIRECTORY_SEPARATOR . "header.php";

class Callback extends tsCore {

	/**
	 * @access public
	 * Puede ser github, discord, google, etc.
	*/
	public $social = '';

	/**
	 * @access public
	*/
	public $social_version = '';
	
	public function __construct() {}

	/**
	 * @name getEndPoint
	 * @access public
	 * @param string
	 * @return string
	 * Manejo de OAuth | Token
	*/
	public function getEndPoint(string $type = '') {
		$extract = parent::getEndPoints($this->social, $type);
		return $extract;
	}

	/**
	 * @name buildQuery
	 * @access public
	 * @return string
	 * Control sobre parámetros en cURL
	*/
	public function buildQuery() {
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT social_name, social_client_id, social_client_secret, social_redirect_uri FROM @social WHERE social_name = '{$this->social}'"));
		$param = [
		 	'client_id' => $data['social_client_id'],
		 	'client_secret' => $data['social_client_secret'],
		 	'grant_type' => 'authorization_code',
		 	'code' => $_GET['code'],
		 	'redirect_uri' => $data['social_redirect_uri']
		];
		// Quitamos 'grant_type'
		if($this->social === 'github' OR $this->social === 'facebook') unset($param['grant_type']);
		if($this->social === 'google') {
			$param['prompt'] = 'consent';
		}
		// Armamos la consulta
		return http_build_query($param);
	}

	public function httpHeader() {
		if($this->social === 'github') {
			$header = ['Accept: application/json'];
		} elseif($this->social === 'discord') {
			$header = ['Content-Type: application/x-www-form-urlencoded'];
		} 
		return $header;
	}

	public function cURLToken(bool $status = true) {
		$ch = curl_init($this->getEndPoint('token'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->buildQuery());
		if($status) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->httpHeader());
		}
		$response = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($response);

		return $data;
	}

	private function httpHeaderUser($data) {
		if($this->social === 'github') {
			$array = [
				"Authorization: token " . $data, 
				"User-Agent: ". parent::getSettings()['titulo']
			];
		} elseif($this->social === 'discord' OR $this->social === 'google') {
			$array = ["Authorization: Bearer " . $data];
		}
		return $array;
	}

	public function cURLUser($data) {
		$token = $data->access_token;
		$url_token_user = $this->getEndPoint('user') . ($this->social === 'facebook' ? "&access_token={$token}" : '');
		$ch = curl_init($url_token_user);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if($this->social !== 'facebook')
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->httpHeaderUser($token));
		$response = curl_exec($ch);
		curl_close($ch);
		$userData = json_decode($response);
		return $userData;
	}

	# ===================================================
	# OAuth
	# Desde acá crearemos, actualizaremos o logueamos
	# ===================================================
	public function OAuthComplete(array $UserData = []) {
		global $tsUser;
		# Verificamos que sea un correo
		if(filter_var($UserData['email'], FILTER_VALIDATE_EMAIL)) {
			# Lo almacenamos en una variable
			$email = parent::setSecure($UserData['email']);
			# Generamos la consulta 
			$usuario = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, u.user_password, u.user_email, u.user_activo, u.user_baneado, m.social_name FROM @miembros AS u LEFT JOIN @miembros_social AS m ON m.social_name = '{$this->social}' WHERE LOWER(user_email) = '$email' OR u.user_id = {$tsUser->uid} LIMIT 1"));
			# Si no existe el usuario => CREAREMOS
			if(empty($usuario)) $this->createNewAccount($UserData);
			# Si existe el usuario y no esta logueado => ACCEDEMOS
			else if(!empty($usuario) AND !$tsUser->is_member) $this->accessAccount($usuario);
			# Si existe el usuario y esta logueado => ACTUALIZAREMOS
			else if(!empty($usuario) AND $tsUser->is_member) $this->updateAccount($usuario, $UserData);
		} else die('Lo lamento, este '.$UserData['email'].' no es un correo v&aacute;lido.');
	}

	/**
	 * @name createNewAccount
	 * @access private
	 * @param array
	 * @return redirect
	 * En caso que no exista el usuario, creamos
	 * su cuenta usando su red social elegida
	*/
	private function createNewAccount(array $UserData = []) {
		$rango = empty(parent::getSettings()['c_reg_rango']) ? 3 : (int)parent::getSettings()['c_reg_rango'];
		$active = (int)parent::getSettings()['c_reg_active'];
		$info = [
			'user_name' => $UserData['nick'], 
			'user_password' => '', 
			'user_email' => $UserData['email'], 
			'user_rango' => $rango, 
			'user_registro' => time(), 
			'user_activo' => $active
		];
		if(insertDataInBase([__FILE__, __LINE__], '@miembros', $info)) {
			$id = db_exec('insert_id');
	     	// Creamos un avatar
			copy("https://ui-avatars.com/api/?name={$UserData['nick']}&background=random&color=fff&size=160&font-size=0.50&bold=false&length=2", TS_AVATAR . "$id.webp");
	     	// INSERTAMOS EL PERFIL
			db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @perfil (`user_id`, `p_avatar`) VALUES ($id, 1)");
	      db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @portal (`user_id`) VALUES ($id)");
	      db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @miembros_social (`social_user_id`, `social_name`, `social_nick`, `social_email`, `social_avatar`) VALUES ($id, '{$this->social}', '{$UserData['nick']}', '{$UserData['email']}', '{$UserData['avatar']}')");
	      //
	      $data = [
	      	'user_id' => $id, 
	      	'user_baneado' => 0,
	      ] + $info;
	      $this->accessAccount($data);
		}
	}

	/**
	 * @name accessAccount
	 * @access private
	 * @param array
	 * @return redirect
	 * Si el usuario ya tiene su cuenta vinculada!
	*/
	private function accessAccount(array $user = []) {
		global $tsUser;
		# Usuario activo?
      if((int)$user['user_activo'] === 0) die('Tienes que activar tu cuenta.');
		# Usuario baneado?
      if((int)$user['user_baneado'] === 1) die('Tu has sido baneado.');
		# Esta vinculado?
		$data = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT social_id FROM @miembros_social WHERE social_name = '{$this->social}' AND social_user_id = {$user['user_id']}"))[0];
	
		if(!empty($data)) {   
			// Actualizamos la session
         $tsUser->sessionUpdate((int)$user['user_id']); 
			/* REDERIGIR */
			parent::redirectTo('./');
		} else die('Tu cuenta no esta vinculada a ' . $this->social);
	}

	/**
	 * @name updateAccount
	 * @access private
	 * @param array
	 * @param array
	 * @return redirect
	 * Si el usuario esta logueado, pero quiere vincular
	 * su cuenta a la red social elegida!
	*/
	private function updateAccount(array $usuario = [], array $UserData = []) {
		# Hacemos consulta para comprobar si esta vinculado o no!
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT social_id, social_user_id, social_name, social_nick FROM @miembros_social WHERE social_name = '{$this->social}' AND social_user_id = {$usuario['user_id']}"));
		# Si no esta vinculado, lo agregaremos
		if(empty($data['social_id'])) {
			db_exec([__FILE__, __LINE__], 'query', "INSERT INTO @miembros_social (`social_user_id`, `social_name`, `social_nick`, `social_email`, `social_avatar`) VALUES ({$usuario['user_id']}, '{$this->social}', '{$UserData['nick']}', '{$UserData['email']}', '{$UserData['avatar']}')");
		}
		parent::redirectTo('./');
	}

	/**
	 * @name getDataUserAttribute
	 * @access private
	 * @param object $param
	 * @param string $attribute
	 * @param string $token
	 * @return string
	 * Obtenemos el avatar o el nickname dependiendo del atributo solicitado
	 */
	private function getDataUserAttribute($param, $attribute, $token = '') {
		switch ($this->social) {
			case 'discord':
				$response = [
		         'avatar' => "https://cdn.discordapp.com/avatars/{$param->id}/{$param->avatar}.png?size=256",
		         'nickname' => $param->global_name
		      ];
			break;
			case 'facebook':
			$user['avatar'] = "https://graph.facebook.com/v20.0/{$userData->id}/picture?type=large&access_token={$data->access_token}"; 
				$response = [
		         'avatar' => "https://graph.facebook.com/{$this->social_version}/{$param->id}/picture?type=large&access_token=$token",
		         'nickname' => $param->short_name
		      ];
			break;
			case 'github':
				$response = [
		         'avatar' => $param->avatar_url,
		         'nickname' => $param->name
		      ];
			break;
			case 'google':
				$response = [
		         'avatar' => str_replace(' ', '', str_replace('s96-c', 's160-c', $param->picture)),
		         'nickname' => str_replace(' ', '', $param->given_name)
		      ];
			break;
		}
	   return $response[$attribute] ?? '';
	}

	/**
	 * @name getDataInfoUser
	 * @access public
	 * @param object $userData
	 * @param string $token
	 * @return array
	 * Obtenemos los datos necesarios nombre, email y avatar
	 */
	public function getDataInfoUser($userData, $token = '') {
	   $user['email'] = $userData->email;
	   $user['nick'] = $this->getDataUserAttribute($userData, 'nickname');
	   $user['avatar'] = $this->getDataUserAttribute($userData, 'avatar', $token);
	   return $user;
	}


}
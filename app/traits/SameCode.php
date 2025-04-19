<?php

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 3.1.18
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

/**
 * Este trait asume que las propiedades $core y $user son inyectadas
 * desde la clase que lo utiliza, típicamente vía constructor.
 */

namespace app\traits;

use app\models\{Core,User};
use app\utils\{Images,Zcode};

trait SameCode {

	protected $core;
	protected $user;
	protected $images;
	protected $zcode;

	public function __construct(Core $core, User $user, Images $images, Zcode $zcode) {
		$this->core = $core;
		$this->user = $user;
		$this->images = $images;
		$this->zcode = $zcode;
	}

	public function generate_password(string $name, string $pass, ?string $hash = '') {
		$options = ['cost' => 12];
		$password = md5($this->sanitize_string($name) . $this->sanitize_string($pass));
		return empty($hash) ? password_hash($password, PASSWORD_DEFAULT, $options) : password_verify($password, $hash);
	}

}
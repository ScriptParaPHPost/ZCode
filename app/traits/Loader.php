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

trait Loader {

	protected $isCoreLoader;
	protected $isUserLoader;
	protected $isImagesLoader;
	protected $isZcodeLoader;

	public function __construct() {
		$this->isCoreLoader = new Core;
		$this->isUserLoader = new User;
		$this->isImagesLoader = new Images;
		$this->isZcodeLoader = new Zcode;
	}

	public function run() {
		return (object)[
			'core' => new Core,
			'user' => new User,
			'images' => new Images,
			'zcode' => new Zcode,
		];
	}


}
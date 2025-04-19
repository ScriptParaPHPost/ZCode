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

namespace app\services;
use Symfony\Component\Clock\NativeClock;
use OTPHP\TOTP;

class TotpService {

	public function verifyCodeSecret(string $secret, int $code): bool|string {
		$clock = new NativeClock();
		$totp = TOTP::createFromSecret($secret, $clock);
		if (strlen($code) !== 6) {
    		return '0: El código debe ser de 6 dígitos numéricos.';
		}
		return $totp->verify($code);
	}

	public function createSecret(string $secret, int $code): bool|string {
		return $this->verifyCodeSecret($secret, $code);
	}

}
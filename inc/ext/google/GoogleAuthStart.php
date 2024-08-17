<?php

/**
 * Con este archivo cargamos los ficheros necesarios
 * para el funcionamiento del 2FA
*/

define('API_CODEQR_GENERATOR', 'https://api.qrserver.com/v1/create-qr-code');

include realpath(__DIR__) . "/FixedBitNotation.php";
include realpath(__DIR__) . "/GoogleAuthenticatorInterface.php";
include realpath(__DIR__) . "/GoogleAuthenticator.php";
include realpath(__DIR__) . "/GoogleQrUrl.php";
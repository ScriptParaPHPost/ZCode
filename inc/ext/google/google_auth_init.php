<?php

/**
 * Con este archivo cargamos los ficheros necesarios
 * para el funcionamiento del 2FA
*/

include realpath(__DIR__) . "/FixedBitNotation.php";
include realpath(__DIR__) . "/GoogleAuthenticatorInterface.php";
include realpath(__DIR__) . "/GoogleAuthenticator.php";
include realpath(__DIR__) . "/GoogleQrUrl.php";
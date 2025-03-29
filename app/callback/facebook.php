<?php

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

require realpath(__DIR__) . DIRECTORY_SEPARATOR . "Callback.php";
$callback = new Callback;

$callback->social = 'facebook';
$callback->social_version = 'v20.0';

$data = $callback->cURLToken(false);

$userData = $callback->cURLUser($data);

$user = $callback->getDataInfoUser($userData, $data->access_token);

$callback->OAuthComplete($user);
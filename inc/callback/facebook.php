<?php 

/**
 * @name Facebook.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v1.8.11
 * @description Para la conexión con facebook
**/

require realpath(__DIR__) . DIRECTORY_SEPARATOR . "Callback.php";
$callback = new Callback;

$callback->social = 'facebook';
$callback->social_version = 'v20.0';

$data = $callback->cURLToken(false);

$userData = $callback->cURLUser($data);

$user = $callback->getDataInfoUser($userData, $data->access_token);

$callback->OAuthComplete($user);
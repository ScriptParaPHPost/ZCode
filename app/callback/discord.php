<?php 

/**
 * @name Discord.php
 * @copyright ZCode 2024
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://zcodev.alwaysdata.net/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v2.0.0
 * @description Para la conexión con discord
**/

require realpath(__DIR__) . DIRECTORY_SEPARATOR . "Callback.php";
$callback = new Callback;

$callback->social = 'discord';

$data = $callback->cURLToken();

$userData = $callback->cURLUser($data);

$user = $callback->getDataInfoUser($userData);

$callback->OAuthComplete($user);
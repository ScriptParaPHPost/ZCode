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

use app\callback\Callback;
$callback = new Callback;

$callback->social = 'discord';

$data = $callback->cURLToken();

$userData = $callback->cURLUser($data);

$user = $callback->getDataInfoUser($userData);

$callback->OAuthComplete($user);
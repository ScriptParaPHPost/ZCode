<?php

require realpath(__DIR__) . DIRECTORY_SEPARATOR . "Callback.php";
$callback = new Callback;

$callback->social = 'github';

$data = $callback->cURLToken();

$userData = $callback->cURLUser($data);

$user['nick'] = $userData->name;
$user['avatar'] = $userData->avatar_url;
$user['email'] = $userData->email;

$callback->OAuthComplete($user);
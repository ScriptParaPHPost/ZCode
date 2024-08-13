<?php

require realpath(__DIR__) . DIRECTORY_SEPARATOR . "Callback.php";
$callback = new Callback;

$callback->social = 'discord';

$data = $callback->cURLToken();

$userData = $callback->cURLUser($data);

$userID = $userData->id; 
$avatarID = $userData->avatar; 

$user['nick'] = $userData->global_name;
$user['email'] = $userData->email;
$user['avatar'] = "https://cdn.discordapp.com/avatars/{$userID}/{$avatarID}.png?size=128";

$callback->OAuthComplete($user);
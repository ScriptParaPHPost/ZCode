<?php

require realpath(__DIR__) . DIRECTORY_SEPARATOR . "Callback.php";
$callback = new Callback;

$callback->social = 'facebook';

$data = $callback->cURLToken(false);

$userData = $callback->cURLUser($data);

$user['nick'] = $userData->short_name;
$user['email'] = $userData->email;
$user['avatar'] = "https://graph.facebook.com/v20.0/{$userData->id}/picture?type=large&access_token={$data->access_token}"; 

$callback->OAuthComplete($user);
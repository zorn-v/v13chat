<?php

$app->get('/login', 'App\\Controller\\Auth::login')->bind('login');
$app->match('/register', 'App\\Controller\\Auth::register')->bind('register');
$app->match('/', 'App\\Controller\\Chat::layout')->bind('chat');

//ajax
$app->post('/users-list', 'App\\Controller\\Ajax::usersList')->bind('users-list');
$app->post('/messages', 'App\\Controller\\Ajax::messages')->bind('messages');
$app->post('/message-delete/{messageId}', 'App\\Controller\\Ajax::messageDelete')->bind('message-delete');
$app->post('/user-voice/{userId}', 'App\\Controller\\Ajax::userVoice')->bind('user-voice');
$app->post('/user-kick/{userId}', 'App\\Controller\\Ajax::userKick')->bind('user-kick');
$app->post('/user-ban/{userId}', 'App\\Controller\\Ajax::userBan')->bind('user-ban');

<?php

$app->get('/login', 'App\\Controller\\Auth::login')->bind('login');
$app->match('/register', 'App\\Controller\\Auth::register')->bind('register');
$app->match('/', 'App\\Controller\\Chat::layout')->bind('chat');

//ajax
$app->post('/users-list', 'App\\Controller\\Ajax::usersList')->bind('users-list');
$app->post('/messages', 'App\\Controller\\Ajax::messages')->bind('messages');
$app->post('/message-delete/{id}', 'App\\Controller\\Ajax::messageDelete')->bind('message-delete');

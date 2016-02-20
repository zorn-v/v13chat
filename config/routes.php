<?php

$app->get('/login', 'App\\Controller\\Auth::login')->bind('login');
$app->match('/register', 'App\\Controller\\Auth::register')->bind('register');
$app->get('/', 'App\\Controller\\Chat::layout')->bind('chat');

<?php

$app->get('/login', 'App\\Controller\\Auth::login');
$app->get('/register', 'App\\Controller\\Auth::register')->bind('register');
$app->get('/', 'App\\Controller\\Chat::layout');

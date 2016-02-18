<?php

$app->get('/login', 'App\\Controller\\Auth::login');
$app->get('/', 'App\\Controller\\Chat::layout');

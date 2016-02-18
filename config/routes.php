<?php

$app->get('/login', 'App\\Controller\\Auth::login');
$app->get('/', function () use($app) {
    return 'You are logged as '.$app['user']->getProfile()->name;
});

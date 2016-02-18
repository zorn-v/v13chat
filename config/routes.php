<?php

$app->get('/login', 'App\\Controller\\Auth::login');
$app->get('/', function () {
    return 'You are logged as '.$app['user']->name;
});

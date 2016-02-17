<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new App\Application();
$app['debug'] = true;

require_once __DIR__ . '/../config/services.php';
require_once __DIR__ . '/../config/routes.php';

$app->run();

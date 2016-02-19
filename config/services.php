<?php

use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return $app['request']->getBasePath().'/'.ltrim($asset, '/');
    }));
    return $twig;
}));
$app->register(new Silex\Provider\SessionServiceProvider());
$app['session.storage.handler'] = new PdoSessionHandler($db->getConnection()->getPdo());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.role_hierarchy' => [
        'ROLE_SUPER_ADMIN' => ['ROLE_ADMIN'],
        'ROLE_ADMIN' => ['ROLE_USER']
    ],
    'security.encoder.digest' => $app->share(function () {
        return new Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder();
    }),
    'security.firewalls' => [
        'default' => [
            'pattern' => '^(?!/login$|/register$).*',
            'form' => ['login_path' => '/login', 'check_path' => '/login_check'],
            'logout' => ['logout_path' => '/logout', 'invalidate_session' => true],
            'users' => $app->share(function () {
                return new App\Security\UserProvider();
            }),
        ]
    ]
));

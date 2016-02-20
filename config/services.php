<?php

use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;

//Сервис генерации урлов по имени роута. Так же становятся доступны twig функции path и url
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
//Шаблонизатор twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
    'twig.form.templates'=> ['form_layout.html.twig'],
));
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return $app['request']->getBasePath().'/'.ltrim($asset, '/');
    }));
    return $twig;
}));

//Сессии
$app->register(new Silex\Provider\SessionServiceProvider());
//Храним в базе
$app['session.storage.handler'] = new PdoSessionHandler($db->getConnection()->getPdo());

//Выборка наследования ролей из базы
//TODO закешировать
$role_hierarchy = [];
$roles = App\Model\Role::whereNotNull('inherit_roles')->get();
foreach ($roles as $role) {
    $role_hierarchy[$role->title] = json_decode($role->inherit_roles);
}
//Аутентификация с авторизацией
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.role_hierarchy' => $role_hierarchy,
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
$app['security.authentication.success_handler.default'] = $app->share(function ($app) {
    return new App\Security\LoginSuccessHandler($app);
});

//Сервис генерации и обработки форм
$app->register(new Silex\Provider\FormServiceProvider());

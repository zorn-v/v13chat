<?php

use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;

//Глобальные переменные из таблицы config
foreach (\App\Model\Config::all() as $config) {
    $app['chat.config.'.$config->name] = $config->value;
}

//Сервис генерации урлов по имени роута. Так же становятся доступны twig функции path и url
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
//Шаблонизатор twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views',
    'twig.form.templates' => ['form_layout.html.twig'],
    'twig.options' => [
        'cache' => __DIR__ . '/../cache/twig',
    ],
));
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $assetFunction = function ($asset) use ($app) {
        return $app['request']->getBasePath().'/'.ltrim($asset, '/');
    };
    $twig->addFunction(new \Twig_SimpleFunction('asset', $assetFunction));
    $twig->addFilter(new \Twig_SimpleFilter('smiles', function ($msg) use ($assetFunction) {
        return preg_replace('#\[smile\](.+?)\[/smile\]#', '<img src="'.$assetFunction('img/smiles').'/\1"/>', $msg);
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

//Проверяльщик прав
$app['chat.rights.voter'] = $app->share(function () {
    return new App\Security\RightsVoter();
});
$app['security.voters'] = $app->extend('security.voters', function($voters) use ($app) {
    $voters[] = $app['chat.rights.voter'];
    return $voters;
});

$app['dispatcher']->addListener(Symfony\Component\HttpKernel\KernelEvents::REQUEST, function() use ($app) {
    //Очистка старых банов
    App\Model\Ban::where('until', '<', new \DateTime())->delete();
    //Удаление просроченных абилок
    App\Model\UserAbility::where('until', '<', new \DateTime())->delete();
    //Выкидывание неактивных
    if ($app['chat.config.inactive_timeout'] > 0) {
        $sessions = App\Model\Session::with('user')->whereNotNull('user_id')->get();
        foreach ($sessions as $session) {
            if ((time() - $session->updated_at->getTimestamp() > $app['chat.config.inactive_timeout'])) {
                $lastMessage = App\Model\Message::where('user_id', $session->user_id)
                    ->orderBy('updated_at', 'DESC')
                    ->limit(1)
                    ->first();
                if ((time() - $lastMessage->updated_at->getTimestamp() > $app['chat.config.inactive_timeout'])) {
                    (new App\Model\Message([
                        'message'=>'Теряет связь и уходит '.$session->user->name.'...'
                    ]))->save();
                    $session->delete();
                    $app['request']->getSession()->invalidate(1);
                }
            }
        }
    }
});

<?php

namespace App\Controller;

use Silex\Application;

class Auth
{
    public function login(Application $app)
    {
        return $app['twig']->render('login.twig.html', ['text' => 'Login page']);
    }
}

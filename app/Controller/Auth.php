<?php

namespace App\Controller;

use Silex\Application;
use App\Model\User;

class Auth
{
    public function login(Application $app)
    {
        $user = User::find(1);
        return $app['twig']->render('login.twig.html', ['user' => $user]);
    }
}

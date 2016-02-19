<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Auth
{
    public function login(Application $app, Request $request)
    {
        return $app['twig']->render('login.html.twig', array(
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }

    public function register(Application $app)
    {
        return $app['twig']->render('register.html.twig');
    }
}

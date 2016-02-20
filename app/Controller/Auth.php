<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Session;

class Auth
{
    public function login(Application $app, Request $request)
    {
        $onlineUsers = [];
        $userSessions = Session::whereNotNull('user_id')->with('user')->get();
        foreach ($userSessions as $session) {
            $onlineUsers[] = $session->user->name;
        }
        return $app['twig']->render('login.html.twig', array(
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
            'online_users'  => $onlineUsers
        ));
    }

    public function register(Application $app)
    {
        return $app['twig']->render('register.html.twig');
    }
}

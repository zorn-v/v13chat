<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Session;

class Ajax
{
    public function usersList(Application $app, Request $request)
    {
        $users = [];
        $sessions = Session::whereNotNull('user_id')->get();
        foreach ($sessions as $session) {
            $users[] = $session->user;
        }
        return $app['twig']->render('ajax/users_list.html.twig', [
            'users' => $users,
        ]);
    }
}

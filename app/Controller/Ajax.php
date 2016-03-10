<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Session;
use App\Model\Message;

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

    public function messages(Application $app, Request $request)
    {
        return $app['twig']->render('ajax/messages.html.twig', [
            'messages' => Message::with('user')->with('recipient')->get(),
        ]);
    }
}

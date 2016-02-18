<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Chat
{
    public function layout(Application $app, Request $request)
    {
        return $app['twig']->render('chat.twig.html');
    }
}

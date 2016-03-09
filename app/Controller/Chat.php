<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Config;

class Chat
{
    public function layout(Application $app, Request $request)
    {
        return $app['twig']->render('chat.html.twig');
    }
}

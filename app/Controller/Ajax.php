<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Ajax
{
    public function usersList(Application $app, Request $request)
    {
        return date('d.m.Y H:i:s');
        return $app['twig']->render('chat.html.twig', [
            'chat_controls' => $form->createView(),
            'smiles' => $smiles,
        ]);
    }
}

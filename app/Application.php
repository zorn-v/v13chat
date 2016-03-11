<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;

class Application extends \Silex\Application
{
    use \Silex\Application\TwigTrait;
    use \Silex\Application\FormTrait;
    use \Silex\Application\SecurityTrait;
    use \Silex\Application\UrlGeneratorTrait;

    public function redirectToRoute($routeName, $parameters)
    {
        return $this->redirect($this->path($routeName, $parameters));
    }

    public function addFlash(Request $request, $text, $type = 'message')
    {
        $request->getSession()->getFlashBag()->add($type, $text);
    }
}

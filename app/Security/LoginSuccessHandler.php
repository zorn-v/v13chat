<?php

namespace App\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use App\Model\Session;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $userId = $this->app['user']->id;
        Session::where('user_id', $userId)->delete();
        $request->getSession()->save();
        $session = Session::find($request->getSession()->getId());
        $session->user_id = $userId;
        $session->ip = $request->getClientip();
        $session->save();
        return $this->app->redirectToRoute('chat');
    }
}

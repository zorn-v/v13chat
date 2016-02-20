<?php

namespace App\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use App\Model\UserSession;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    protected $app;
	
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $request->getSession()->save();
        $session = new UserSession();
        $session->sess_id = $request->getSession()->getId();
        $session->user_id = $this->app['user']->getProfile()->id;
        $session->ip = $request->getClientip();
        $session->save();
        return new RedirectResponse('/');
    }
}

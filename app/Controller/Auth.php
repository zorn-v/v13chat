<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use App\Model\Session;
use App\Model\User;
use App\Security\AuthUser;

class Auth
{
    public function login(Application $app, Request $request)
    {
        $onlineUsers = [];
        $userSessions = Session::whereNotNull('user_id')->with('user')->get();
        foreach ($userSessions as $session) {
            $onlineUsers[] = $session->user->name;
        }
        return $app['twig']->render('login.html.twig', [
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
            'online_users'  => $onlineUsers
        ]);
    }

    public function register(Application $app, Request $request)
    {
        $form = $app->form(['gender'=>0])
            ->add('name')
            ->add('pass', PasswordType::class)
            ->add('pass_verify', PasswordType::class)
            ->add('gender', ChoiceType::class, [
                'choices' => ['?'=>0, 'М'=>1, 'Ж'=>2],
                'expanded' => true,
            ])
            ->add('realname', null, ['required'=>false])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                'required'=>false,
            ])
            ->add('email', EmailType::class, ['required'=>false])
            ->add('icq', null, ['required'=>false])
            ->add('site', UrlType::class, ['required'=>false])
            ->getForm();

        $form->handleRequest($request);

        $errors = [];
        if ($form->isValid()) {
            $data = $form->getData();
            if (User::where('name', $data['name'])->first() !== null) {
                $errors[] = 'Такой ник уже занят, выберите другой';
            }
            if (strlen($data['name'])<4) {
                $errors[] = 'Длина ника от 4 символов';
            }
            if (empty($data['pass']) || $data['pass']!=$data['pass_verify']) {
                $errors[] = 'Пароли не заданы или не совпадают';
            }
            if (count($errors)==0) {
                $user = new User();
                $user->name = $data['name'];
                $user->role_id = 1;
                $user->gender = $data['gender'];
                $user->realname = $data['realname'];
                $user->birthday = $data['birthday'];
                $user->email = $data['email'];
                $user->icq = $data['icq'];
                $user->site = $data['site'];

                $authUser = new AuthUser($user);
                $user->pass = $app->encodePassword($authUser, $data['pass']);

                $user->save();

                $request->getSession()->getFlashBag()->add('message', 'Вы успешно зарегистрированы. Можете войти в чат со своим логином и паролем.');

                return $app->redirect($app['url_generator']->generate('login'));
            }
        }
        return $app['twig']->render('register.html.twig', [
            'form' => $form->createView(),
            'errors' => $errors
        ]);
    }
}

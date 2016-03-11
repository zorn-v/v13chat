<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Session;
use App\Model\Message;
use App\Model\Ban;

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
        $userId = $app['user']->id;
        $messages = Message::with('user')
            ->with('recipient')
            ->whereNull('recipient_id')
            ->orWhere('recipient_id', $userId)
            ->orWhere('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->get();
        return $app['twig']->render('ajax/messages.html.twig', [
            'messages' => $messages,
        ]);
    }

    public function messageDelete(Application $app, Request $request, $messageId)
    {
        if ($app->isGranted('ROLE_MODERATOR')) {
            Message::destroy($messageId);
        }
        return '';
    }

    public function userVoice(Application $app, Request $request, $userId)
    {
        if ($app->isGranted('ROLE_REGISTRATOR')) {
            Ban::where('user_id', $userId)->where('reason', Ban::REASON_SILENT)->delete();
        }
        return '';
    }
}

<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Session;
use App\Model\Message;
use App\Model\User;
use App\Model\Ban;
use App\Model\Log;

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

    public function userKick(Application $app, Request $request, $userId)
    {
        $user = User::find($userId);
        $this->userBlock($app, $user, Ban::REASON_KICK, '+1 minute');
        (new Message([
            'message' => '<b><span style="color:red;">'.$app['user']->role->description.'</span></b>: '.
                         'пользователь с ником <span style="color:red;">'.$user->name.'</span> получает предупреждение!'
        ]))->save();
        return '';
    }

    public function userBan(Application $app, Request $request, $userId)
    {
        $user = User::find($userId);
        $this->userBlock($app, $user, Ban::REASON_BAN, '+90 minute');
        (new Message([
            'message' => '<b><span style="color:red;">'.$app['user']->role->description.'</span></b>: '.
                         'пользователь с ником <span style="color:red;">'.$user->name.'</span>  удален из чата!'
        ]))->save();
        return '';
    }

    private function userBlock(Application $app, User $user, $reason, $blockUntil)
    {
        if ($app->isGranted('ROLE_MODERATOR') && $app->isGranted('CAN_BAN', $user)) {
            Session::where('user_id', $user->id)->delete();
            $blockTime = new \DateTime($blockUntil);
            $ban = new Ban();
            $ban->user_id = $user->id;
            $ban->author_id = $app['user']->id;
            $ban->reason = $reason;
            $ban->until = $blockTime;
            $ban->save();
        }
    }
}

<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Model\Smile;
use App\Model\Message;
use App\Model\User;

class Chat
{
    public function layout(Application $app, Request $request)
    {
        $form = $app->form(['color' => $request->cookies->get('v13chat-msg-color')])
            ->add('clear', ButtonType::class)
            ->add('to', TextType::class, ['required' => false])
            ->add('message', TextType::class)
            ->add('smiles_button', ButtonType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        if ($app->isGranted('ROLE_MODERATOR')) {
            $form->add('color', ChoiceType::class, [
                'choices' => $app['user']->getAvailColors(),
                'choice_attr' => function($val, $key, $index) {
                    return ['style' => 'background-color: '.$val];
                },
            ]);
        }

        $smiles = [];
        foreach (Smile::all() as $smile) {
            $smiles[$smile->text] = $smile->img;
        }

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();

            if (!empty($data['color'])) {
                setcookie('v13chat-msg-color', $data['color']);
            }
            if (empty($data['to']) && (!$app->isGranted('ROLE_USER') || $app['user']->isSilent())) {
                $app->addFlash($request, 'На вас молчанка! Общайтесь только приватно.');
                throw new \RuntimeException('Ajax reload');
            }
            $msg = htmlspecialchars($data['message']);
            $msg = preg_replace('#https?://[^\s]+#', '<a href="\0" target="_blank">\0</a>', $msg);
            $msg = str_replace(
                array_keys($smiles),
                array_map(
                    function ($img) {
                        return '[smile]'.$img.'[/smile]';
                    },
                    array_values($smiles)
                ),
                $msg
            );

            $message = new Message();
            $message->user_id = $app['user']->id;
            if (!empty($data['to'])) {
                $recipient = User::where('name', $data['to'])->first();
                if ($recipient) {
                    $message->recipient_id = $recipient->id;
                }
            }
            if ($app->isGranted('ROLE_MODERATOR') && !empty($data['color'])) {
                $msg = '<span style="color:'.$data['color'].';">'.$msg.'</span>';
            }
            $message->message = $msg;
            $message->save();
            return '';
        }

        return $app['twig']->render('chat.html.twig', [
            'chat_controls' => $form->createView(),
            'smiles' => $smiles,
        ]);
    }
}

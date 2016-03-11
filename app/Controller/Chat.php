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

class Chat
{
    public function layout(Application $app, Request $request)
    {
        $form = $app->form()
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

        return $app['twig']->render('chat.html.twig', [
            'chat_controls' => $form->createView(),
            'smiles' => $smiles,
        ]);
    }
}

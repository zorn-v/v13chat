<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class Chat
{
    public function layout(Application $app, Request $request)
    {
        $form = $app->form()
            ->add('clear', ResetType::class)
            ->add('to', TextType::class, ['required' => false])
            ->add('message', TextType::class)
            ->add('smiles_button', ButtonType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        if ($app->isGranted('ROLE_MODERATOR')) {
            $choices = ['White (1)'=>'white', 'Pink (3)'=>'pink', 'Limegreen (3)'=>'limegreen'];
            if ($app->isGranted('ROLE_REGISTRATOR')) {
                $choices['Yellow (4)'] = 'yellow';
                $choices['Red (4)'] = 'red';
            }
            if ($app->isGranted('ROLE_ADMIN')) {
                $choices['Aqua (5)'] = 'aqua';
            }
            $form->add('color', ChoiceType::class, [
                'choices' => $choices,
                'choice_attr' => function($val, $key, $index) {
                    return ['style' => 'background-color: '.$val];
                },
            ]);
        }

        return $app['twig']->render('chat.html.twig', ['chat_controls' => $form->createView()]);
    }
}

<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Security\AuthUser;
use App\Model\Ability;

class AbilityVoter extends Voter
{
    private static $supports = null;

    protected function supports($attribute, $subject)
    {
        if (self::$supports === null) {
            $abilities = Ability::all();
            foreach ($abilities as $abil) {
                self::$supports[] = $abil->name;
            }
        }
        if (!in_array($attribute, self::$supports)) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof AuthUser) {
            return false;
        }
        //var_dump($user->getProfile()->whereHas('abilities', function ($q) {
            //$q->where('name', $attribute);
        //})->toSql());
        //var_dump($user->getProfile()->abilities());
    }
}
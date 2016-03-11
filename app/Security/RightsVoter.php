<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Model\User;

class RightsVoter extends Voter
{
    const CAN_KICK = 'CAN_KICK';
    const CAN_BAN  = 'CAN_BAN';

    protected function supports($attribute, $subject)
    {
        if (!$subject instanceof User) {
            return false;
        }
        $supports = [
            self::CAN_KICK,
            self::CAN_BAN,
        ];
        if (!in_array($attribute, $supports)) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }
        /** @var User $victim */
        $victim = $subject;
        switch($attribute) {
            //TODO отвязать от role_id
            case self::CAN_KICK:
            case self::CAN_BAN:
                return $user->role_id > $victim->role_id;
        }

        throw new \LogicException('Unknown attribute in RightsVoter vote');
    }
}

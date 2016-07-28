<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use App\Model\User;
use App\Model\Ban;

class UserProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        $user = User::where('name', $username)->first();
        if ($user === null) {
            throw new UsernameNotFoundException(
                sprintf('User with username "%s" not found', $username)
            );
        }
        $ban = Ban::where('user_id', $user->id)->orderBy('until', 'DESC')->first();
        if ($ban) {
            throw new LockedException(
                sprintf('Ваш аккаунт заблокирован. Осталось %d мин.', $ban->until->diffInMinutes())
            );
        }
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'App\Model\User';
    }
}
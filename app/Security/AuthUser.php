<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

use App\Model\User;

class AuthUser implements UserInterface
{
    private $username;
    private $password;
    private $roles;
    private $profile;
    private $abilities = [];

    public function __construct(User $profile)
    {
        $this->username = $profile->name;
        $this->password = $profile->pass;
        $this->roles = [$profile->role->title];
        $this->profile = $profile;

        foreach ($profile->abilities as $ability) {
            $this->abilities[$ability->name] = $ability->pivot->data;
        }
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        $this->password = null;
    }

    public function getProfile()
    {
        return $this->profile;
    }

    public function getAbility($ability)
    {
        return isset($this->abilities[$ability]) ? $this->abilities[$ability] : null;
    }
}

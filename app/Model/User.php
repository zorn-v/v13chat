<?php

namespace App\Model;

use Symfony\Component\Security\Core\User\UserInterface;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements UserInterface
{
    private $userAbils = null;
    protected $hidden = ['pass'];

    public function role()
    {
        return $this->belongsTo('App\\Model\\Role');
    }

    public function abilities()
    {
        return $this->belongsToMany('App\\Model\\Ability', 'user_abilities')->withPivot('data');
    }

    public function bans()
    {
        return $this->hasMany('App\\Model\\Ban');
    }

    //TODO отвязать от role_id
    public function getAvailColors()
    {
        $colors = [];
        if ($this->role_id > 2) {
            $colors = [
                'White (1)' => 'white',
                'Pink (3)' => 'pink',
                'Limegreen (3)' => 'limegreen',
            ];
            if ($this->role_id > 3) {
                $colors['Yellow (4)'] = 'yellow';
                $colors['Red (4)'] = 'red';
                if ($this->role_id > 4) {
                    $colors['Aqua (5)'] = 'aqua';
                }
            }
        }
        return $colors;
    }

    public function getAbility($userAbil)
    {
        if ($this->userAbils === null) {
            foreach ($this->abilities as $ability) {
                $this->userAbils[$ability->name] = $ability->pivot->data;
            }
        }
        return isset($this->userAbils[$userAbil]) ? $this->userAbils[$userAbil] : null;
    }

    public function isSilent()
    {
        return $this->bans()
            ->where('reason', Ban::REASON_SILENT)
            ->first() !== null;
    }

    public function getPassword()
    {
        return $this->pass;
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
        return $this->name;
    }

    public function getRoles()
    {
        return [$this->role->title];
    }

    public function eraseCredentials()
    {
    }
}

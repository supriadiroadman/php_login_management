<?php

namespace Supriadi\BelajarPhpMvc\Model;

use Supriadi\BelajarPhpMvc\Domain\User;

class UserPasswordUpdateResponse
{
    private User $user;


    public function getUser(): User
    {
        return $this->user;
    }


    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
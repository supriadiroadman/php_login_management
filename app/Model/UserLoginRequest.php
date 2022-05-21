<?php

namespace Supriadi\BelajarPhpMvc\Model;

class UserLoginRequest
{
    private ?string $id = null;
    private ?string $password = null;

    public function getId(): ?string
    {
        return $this->id;
    }


    public function setId(?string $id): void
    {
        $this->id = $id;
    }


    public function getPassword(): ?string
    {
        return $this->password;
    }


    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }
}
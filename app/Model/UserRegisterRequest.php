<?php

namespace Supriadi\BelajarPhpMvc\Model;

class UserRegisterRequest
{
    private ?string $id = null;
    private ?string $name = null;
    private ?string $password = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
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
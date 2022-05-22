<?php

namespace Supriadi\BelajarPhpMvc\Model;

class UserProfileUpdateRequest
{
    private ?string $id = null;
    private ?string $name = null;


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


}
<?php

namespace Supriadi\BelajarPhpMvc\Model;

class UserPasswordUpdateRequest
{
    private ?string $id = null;
    private ?string $oldPassword = null;
    private ?string $newPassword = null;


    public function getId(): ?string
    {
        return $this->id;
    }


    public function setId(?string $id): void
    {
        $this->id = $id;
    }


    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }


    public function setOldPassword(?string $oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }


    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }


    public function setNewPassword(?string $newPassword): void
    {
        $this->newPassword = $newPassword;
    }
}
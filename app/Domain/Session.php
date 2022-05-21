<?php

namespace Supriadi\BelajarPhpMvc\Domain;

class Session
{
    private string $id;
    private string $userId;

    public function getId(): string
    {
        return $this->id;
    }


    public function setId(string $id): void
    {
        $this->id = $id;
    }


    public function getUserId(): string
    {
        return $this->userId;
    }


    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }
}
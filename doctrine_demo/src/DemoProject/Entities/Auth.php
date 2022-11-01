<?php


namespace DemoProject\Entities;

use DateTime;

class Auth
{
    private int|null $id = null;

    private string $login;

    private string $passwd;

    private DateTime|null $last_auth = null;

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getPasswd(): string
    {
        return $this->passwd;
    }

    public function setPasswd(string $passwd): void
    {
        $this->passwd = $passwd;
    }

    public function getLastAuth(): DateTime
    {
        return $this->last_auth;
    }

    public function setLastAuth(): void
    {
        $this->last_auth = new DateTime();
    }
}
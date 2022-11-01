<?php


namespace DemoProject\Entities;


class Employee
{
    private int|null $id = null;

    private string $fio;

    private int $phone;

    private string $post;

    private Office $office;

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getFio(): string
    {
        return $this->fio;
    }

    public function setFio(string $fio): void
    {
        $this->fio = $fio;
    }

    public function getPhone(): int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): void
    {
        $this->phone = $phone;
    }

    public function getPost(): string
    {
        return $this->post;
    }

    public function setPost(string $post): void
    {
        $this->post = $post;
    }

    public function getOffice(): Office
    {
        return $this->office;
    }

    /*TODO mb use office id instead of class*/
    public function setOffice(Office $office): void
    {
        $this->office = $office;
    }

}
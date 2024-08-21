<?php

namespace Luminar\Database\Tests;

use Luminar\Database\ORM\Column;
use Luminar\Database\ORM\Entity;

#[Entity("entity")]
class ExampleEntity
{
    #[Column(name: "id")]
    private int $id;

    #[Column(name: "message")]
    private string $message;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }


}
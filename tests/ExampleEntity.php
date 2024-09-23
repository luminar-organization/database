<?php

namespace Luminar\Database\Tests;

use Luminar\Database\ORM\Column;
use Luminar\Database\ORM\Entity;
use Luminar\Database\ORM\TypesAttributes;

#[Entity("entity")]
class ExampleEntity
{
    #[Column(name: "id")]
    private int $id;

    #[Column(name: "message", length: 50)]
    private string $message;

    #[Column(name: "content", type: TypesAttributes::TYPE_LONGTEXT)]
    private string $content;

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

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}
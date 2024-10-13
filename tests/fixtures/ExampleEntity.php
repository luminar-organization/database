<?php

namespace Luminar\Database\Tests\fixtures;

use Luminar\Database\ORM\Column;
use Luminar\Database\ORM\Entity;
use Luminar\Database\ORM\TypesAttributes;

#[Entity("entity")]
class ExampleEntity
{
    #[Column(name: "id")]
    private int $id;

    #[Column(name: "name", unique: true, type: TypesAttributes::TYPE_VARCHAR, length: 50)]
    private string $name;

    #[Column(name: "message",type: TypesAttributes::TYPE_VARCHAR, length: 50)]
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
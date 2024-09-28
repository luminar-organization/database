<?php

namespace Luminar\Database\Tests\fixtures;

use Luminar\Database\ORM\Column;
use Luminar\Database\ORM\Entity;

#[Entity(name: "source_relationship")]
class SourceRelationship
{
    #[Column(name: "id")]
    protected int $id;

    #[Column(name: "name")]
    protected string $username;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
}
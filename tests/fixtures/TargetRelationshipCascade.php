<?php

namespace Luminar\Database\Tests\fixtures;

use Luminar\Database\ORM\Column;
use Luminar\Database\ORM\DatabaseRelationship;
use Luminar\Database\ORM\Entity;
use Luminar\Database\ORM\RelationshipTypes;

#[Entity(name: "target_relationship_cascade")]
class TargetRelationshipCascade
{
    #[Column(name: "id")]
    protected int $id;

    #[Column(name: "userId",databaseRelationship: new DatabaseRelationship("source_relationship", "id", RelationshipTypes::RELATIONSHIP_CASCADE))]
    protected int $userId;

    #[Column(name: "text")]
    protected string $text;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }
}
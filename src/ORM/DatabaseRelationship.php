<?php

namespace Luminar\Database\ORM;

class DatabaseRelationship
{
    /**
     * @var string $sourceTable
     */
    protected string $sourceTable;

    /**
     * @var string $sourceColumn
     */
    protected string $sourceColumn;

    /**
     * @var ?string $onDelete
     */
    protected ?string $onDelete;

    /**
     * @var ?string $onUpdate
     */
    protected ?string $onUpdate;

    public function __construct(string $sourceTable, string $sourceColumn, ?string $onDelete = null, ?string $onUpdate = null)
    {
        $this->sourceTable = $sourceTable;
        $this->sourceColumn = $sourceColumn;
        $this->onDelete = $onDelete;
        $this->onUpdate = $onUpdate;
    }

    /**
     * @return ?string
     */
    public function getOnDelete(): ?string
    {
        return $this->onDelete ?? null;
    }

    /**
     * @return ?string
     */
    public function getOnUpdate(): ?string
    {
        return $this->onUpdate ?? null;
    }

    /**
     * @return string
     */
    public function getSourceColumn(): string
    {
        return $this->sourceColumn;
    }

    /**
     * @return string
     */
    public function getSourceTable(): string
    {
        return $this->sourceTable;
    }
}
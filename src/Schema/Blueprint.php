<?php

namespace Luminar\Database\Schema;

class Blueprint
{
    /**
     * @var string $table
     */
    protected string $table;
    /**
     * @var array $columns
     */
    protected array $columns = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function addColumn(string $type, string $name, array $options = []): void
    {
        $this->columns[] = compact('type', 'name', 'options');
    }

    /**
     * @return string
     */
    public function toSql(): string
    {
        $sql = "CREATE TABLE {$this->table} (";

        $columnSql = array_map(function ($column) {
            return "{$column['name']} {$column['type']}";
        }, $this->columns);

        $sql .= implode(', ', $columnSql);
        $sql .= ");";

        return $sql;
    }
}
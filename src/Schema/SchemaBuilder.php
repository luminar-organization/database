<?php

namespace Luminar\Database\Schema;

class SchemaBuilder
{
    /**
     * @param string $tableName
     * @param callable $callback
     * @return string
     */
    public function create(string $tableName, callable $callback): string
    {
        $blueprint = new Blueprint($tableName);
        $callback($blueprint);

        return $blueprint->toSql();
    }

    /**
     * @param string $tableName
     * @return string
     */
    public function drop(string $tableName): string
    {
        return "DROP TABLE IF EXISTS {$tableName};";
    }
}
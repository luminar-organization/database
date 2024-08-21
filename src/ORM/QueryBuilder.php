<?php

namespace Luminar\Database\ORM;

class QueryBuilder
{
    /**
     * @var string $table
     */
    protected string $table;

    /**
     * @var array $conditions
     */
    protected array $conditions = [];

    /**
     * @var int $limit
     */
    protected int $limit;

    /**
     * @var array $updateColumns
     */
    protected array $updateColumns = [];

    /**
     * @param string $table
     * @return $this
     */
    public function table(string $table): static
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param string $column
     * @param string $operator
     * @param $value
     * @return $this
     */
    public function where(string $column, string $operator, $value): static
    {
        $this->conditions[] = "$column $operator $value";
        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param string $column
     * @param string $value
     * @return $this
     */
    public function set(string $column, string $value): static
    {
        $this->updateColumns[] = "$column = '$value'";
        return $this;
    }

    /**
     * @return string
     */
    public function get(): string
    {
        $sql = "SELECT * FROM {$this->table}";
        if(!empty($this->conditions)) {
            $sql .= " WHERE "  . implode(" AND ", $this->conditions);
        }
        if($this->limit) {
            $sql .= " LIMIT $this->limit";
        }
        $sql .= ";";
        return $sql;
    }

    /**
     * @return string
     */
    public function update(): string
    {
        $sql = "UPDATE {$this->table} SET ";
        $sql .= implode(", ", $this->updateColumns);
        if(!empty($this->conditions)) {
            $sql .= " WHERE "  . implode(" AND ", $this->conditions);
        }
        $sql .= ";";
        return $sql;
    }

    /**
     * @return string
     */
    public function remove(): string
    {
        $sql = "DELETE FROM {$this->table}";
        if(!empty($this->conditions)) {
            $sql .= " WHERE "  . implode(" AND ", $this->conditions);
        }
        $sql .= ";";
        return $sql;
    }

    public function insert(): string
    {
        $sql = "INSERT INTO {$this->table} ";
        $values = [];
        foreach($this->updateColumns as $updateColumn) {
            $updateColumn = explode(' = ', $updateColumn);
            $values[$updateColumn[0]] = $updateColumn[1];
        }
        $sql .= "(";
        $count = 0;
        foreach($values as $column => $value) {
            $count += 1;
            if($count == count($values)) {
                $sql .= "$column)";
            } else {
                $sql .= "$column, ";
            }
        }
        $sql .= " VALUES (";
        $count = 0;
        foreach($values as $column => $value) {
            $count += 1;
            if($count == count($values)) {
                $sql .= "$value)";
            } else {
                $sql .= "$value, ";
            }
        }
        $sql .= ";";
        return $sql;
    }
}
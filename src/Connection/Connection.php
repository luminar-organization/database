<?php

namespace Luminar\Database\Connection;

use PDO;
use PDOStatement;

class Connection
{
    protected PDO $pdo;

    public function __construct($dsn, $username = null, $password = null, $options = [])
    {
        $this->pdo = new PDO($dsn, $username, $password, $options);
    }

    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * @param string $sql
     * @return false|PDOStatement
     */
    public function query(string $sql): false|PDOStatement
    {
        return $this->pdo->query($sql);
    }
}
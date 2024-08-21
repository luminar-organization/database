<?php

namespace Luminar\Database\Tests;

use Luminar\Database\Connection\Connection;
use PDO;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    public function testConnectionCanBeEstablished()
    {
        $connection = new Connection("sqlite::memory:", 'root');
        $this->assertInstanceOf(PDO::class, $connection->getPdo());
    }

    public function testQueryExecution()
    {
        $connection = new Connection("sqlite::memory:");
        $result = $connection->query("SELECT sqlite_version();");
        $this->assertNotFalse($result);
        $execute = $result->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotNull($execute);
    }
}
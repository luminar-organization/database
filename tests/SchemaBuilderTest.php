<?php

namespace Luminar\Database\Tests;

use Luminar\Database\Schema\Blueprint;
use Luminar\Database\Schema\SchemaBuilder;
use PHPUnit\Framework\TestCase;

class SchemaBuilderTest extends TestCase
{
    public function testSchemaBuilderCreate()
    {
        $schemaBuilder = new SchemaBuilder();
        $sql = $schemaBuilder->create("users", function (Blueprint $table) {
            $table->addColumn("varchar(255)", "username");
        });
        $this->assertEquals("CREATE TABLE users (username varchar(255));", $sql);
    }

    public function testSchemaBuilderDrop()
    {
        $schemaBuilder = new SchemaBuilder();
        $sql = $schemaBuilder->drop("users");
        $this->assertEquals("DROP TABLE IF EXISTS users;", $sql);
    }
}
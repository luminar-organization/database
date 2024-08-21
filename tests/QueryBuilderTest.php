<?php

namespace Luminar\Database\Tests;

use Luminar\Database\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    public function testSelect()
    {
        $queryBuilder = new QueryBuilder();
        $sql = $queryBuilder->table('users')->where("id", '=', 1)->limit(1)->get();
        $this->assertEquals("SELECT * FROM users WHERE id = 1 LIMIT 1;", $sql);
    }

    public function testUpdate()
    {
        $queryBuilder = new QueryBuilder();
        $sql = $queryBuilder->table("users")->set("username", "admin")->where("id", '=', 1)->update();
        $this->assertEquals("UPDATE users SET username = 'admin' WHERE id = 1;", $sql);
    }

    public function testRemove()
    {
        $queryBuilder = new QueryBuilder();
        $sql = $queryBuilder->table("users")->where('id','=', 1)->remove();
        $this->assertEquals("DELETE FROM users WHERE id = 1;", $sql);
    }

    public function testInsert()
    {
        $queryBuilder = new QueryBuilder();
        $sql = $queryBuilder->table("users")->set("username", "admin")->insert();
        $this->assertEquals("INSERT INTO users (username) VALUES ('admin');", $sql);
    }
}
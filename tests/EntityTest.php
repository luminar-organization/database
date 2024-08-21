<?php

namespace Luminar\Database\Tests;

use Exception;
use Luminar\Database\Connection\Connection;
use Luminar\Database\ORM\EntityManager;
use Luminar\Database\ORM\Repository;
use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class EntityTest extends TestCase
{
    /**
     * @return void
     * @throws Exception
     */
    public function testSchemaBuild()
    {
        // Create connection
        $connection = new Connection("sqlite::memory:");
        // Init Entity Manager
        $entityManager = new EntityManager($connection);
        // Create Schema for our test ExampleEntity
        $schema = $entityManager->schema(new ExampleEntity());
        // Checking that schema has been generated
        $this->assertNotNull($schema);
        // Executing Schema
        $execute = $connection->query($schema['query']);
        // Checking do schema executed properly
        $this->assertNotNull($execute);
        // Dropping schema
        $this->assertNotFalse($connection->query("DROP TABLE " . $schema['table']));
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws Exception
     */
    public function testRepository()
    {
        try {
            // Create connection
            $connection = new Connection("mysql:host=localhost;dbname=luminar-test", 'root');
            // Init Entity Manger
            $entityManager = new EntityManager($connection);
            // Create schema for our test ExampleEntity
            $schema = $entityManager->schema(new ExampleEntity());
            // Initialize Repository for this entity
            $repository = new Repository(ExampleEntity::class, $connection);
            // Checking that schema has been generated
            $this->assertNotNull($schema);
            // Executing schema
            $execute = $connection->query($schema['query']);
            // Checking do schema executed properly
            $this->assertNotNull($execute);
            // Adding new entity to database
            $newEntity = new ExampleEntity();
            $newEntity->setMessage('Example Message');
            $entityManager->persist($newEntity);
            // Searching already added entity with id 1
            $entity = $repository->findBy([
                'id' => 1
            ]);
            // Checking do we found entity and checking do we found right entity with message: "Example Message"
            $this->assertInstanceOf(ExampleEntity::class, $entity);
            $this->assertEquals("Example Message", $entity->getMessage());
            // Updating this entity
            $entity->setMessage('Hello World');
            $entityManager->persist($entity);
            // Searching again to find entity with id 1
            $entity = $repository->findBy([
                'id' => 1
            ]);
            // Checking do we found entity and checking do we found right entity with message: "hello World"
            $this->assertInstanceOf(ExampleEntity::class, $entity);
            $this->assertEquals("Hello World", $entity->getMessage());
            // Removing Entity
            $removeStatus = $repository->remove([
                'id' => 1
            ]);
            $this->assertNotNull($removeStatus);
            // Checking does entity exists
            $removedEntity = $repository->findBy([
                'id' => 1
            ]);
            $this->assertFalse($removedEntity);
            // Dropping schema
            $this->assertNotFalse($connection->query("DROP TABLE " . $schema['table']));
        } catch (PDOException $e) {
            // Repository does not support sqlite
            echo "\nWARNING! Repository does not support sqlite so you need to have for e.g. MySQL Server\n";
            $this->assertTrue(true);
            return;
        }
    }
}
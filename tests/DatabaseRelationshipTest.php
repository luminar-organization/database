<?php

namespace Luminar\Database\Tests;

use Exception;
use Luminar\Database\Connection\Connection;
use Luminar\Database\ORM\EntityManager;
use Luminar\Database\ORM\Repository;
use Luminar\Database\Tests\fixtures\SourceRelationship;
use Luminar\Database\Tests\fixtures\TargetRelationship;
use Luminar\Database\Tests\fixtures\TargetRelationshipCascade;
use Luminar\Database\Tests\fixtures\TargetRelationshipRestrict;
use Luminar\Database\Tests\fixtures\TargetRelationshipSetDefault;
use Luminar\Database\Tests\fixtures\TargetRelationshipSetNull;
use PDOException;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class DatabaseRelationshipTest extends TestCase
{
    /**
     * @var EntityManager $entityManager
     */
    protected EntityManager $entityManager;

    /**
     * @var Connection $connection
     */
    protected Connection $connection;

    /**
     * @var bool $enabled
     */
    protected bool $enabled = false;

    protected function setUp(): void
    {
        try {
            $this->connection = new Connection("mysql:host=localhost;dbname=luminar-test", "root");
            $this->entityManager = new EntityManager($this->connection);
            $this->enabled = true;
        } catch (PDOException $e) {
            $this->enabled = false;
        }
    }

    /**
     * This test just shouldn't output any errors
     *
     * @return void
     * @throws Exception
     */
    public function testRelationshipSchema()
    {
        if(!$this->enabled) {
            $this->assertTrue(true);
            return;
        }

        // Setting up database
        $schema = $this->entityManager->schema(new SourceRelationship());
        $this->connection->query($schema['query']);

        $schema2 = $this->entityManager->schema(new TargetRelationship());
        $this->connection->query($schema2['query']);

        // Removing tables
        $this->assertNotFalse($this->connection->query("DROP TABLE target_relationship"));
        $this->assertNotFalse($this->connection->query("DROP TABLE source_relationship"));
    }

    /**
     * This test checks do target will be deleted also while deleting source
     *
     * @return void
     * @throws Exception
     * @throws ReflectionException
     */
    public function testRelationshipCascade()
    {
        if(!$this->enabled) {
            $this->assertTrue(true);
            return;
        }

        // Setting up database
        $schema = $this->entityManager->schema(new SourceRelationship());
        $this->assertNotFalse($this->connection->query($schema['query']));

        $schema2 = $this->entityManager->schema(new TargetRelationshipCascade());
        $this->assertNotFalse($this->connection->query($schema2['query']));

        // Adding new source relationship
        $sourceRelationship = new SourceRelationship();
        $sourceRelationship->setUsername("Hello World!");

        $this->entityManager->persist($sourceRelationship);

        // Adding new target relationship
        $targetRelationshipCascade = new TargetRelationshipCascade();
        $targetRelationshipCascade->setUserId(1);
        $targetRelationshipCascade->setText("Hello World!");

        $this->entityManager->persist($targetRelationshipCascade);

        $repositorySource = new Repository(SourceRelationship::class, $this->connection);
        $repositoryTarget = new Repository(TargetRelationshipCascade::class, $this->connection);

        // Removing result from source table
        $this->assertTrue($repositorySource->remove([
            'name' => 'Hello World!'
        ]));

        // Checking target has been deleted also
        $this->assertFalse($repositoryTarget->findBy([
            'text' => 'Hello World!'
        ]));

        // Removing tables
        $this->assertNotFalse($this->connection->query("DROP TABLE target_relationship_cascade"));
        $this->assertNotFalse($this->connection->query("DROP TABLE source_relationship"));
    }

    /**
     * This test checks that after deleting source result target column result will be null
     *
     * @return void
     * @throws Exception
     * @throws ReflectionException
     */
    public function testRelationshipSetNull()
    {
        if(!$this->enabled) {
            $this->assertTrue(true);
            return;
        }

        // Setting up database
        $schema = $this->entityManager->schema(new SourceRelationship());
        $this->assertNotFalse($this->connection->query($schema['query']));

        $schema2 = $this->entityManager->schema(new TargetRelationshipSetNull());
        $this->assertNotFalse($this->connection->query($schema2['query']));

        // Adding new source relationship
        $sourceRelationship = new SourceRelationship();
        $sourceRelationship->setUsername("Hello World!");

        $this->entityManager->persist($sourceRelationship);

        // Adding new target relationship
        $targetRelationshipSetNull = new TargetRelationshipSetNull();
        $targetRelationshipSetNull->setUserId(1);
        $targetRelationshipSetNull->setText("Hello World!");

        $this->entityManager->persist($targetRelationshipSetNull);

        $repositorySource = new Repository(SourceRelationship::class, $this->connection);
        $repositoryTarget = new Repository(TargetRelationshipSetNull::class, $this->connection);

        // Removing result from source table
        $this->assertTrue($repositorySource->remove([
            'id' => 1
        ]));

        /**
         * @var TargetRelationshipSetNull $target
         */
        $target = $repositoryTarget->findBy([
            'text' => 'Hello World!'
        ]);
        $this->assertNull($target->getUserId()); // Checking getter will return null

        // Removing tables
        $this->assertNotFalse($this->connection->query("DROP TABLE target_relationship_set_null"));
        $this->assertNotFalse($this->connection->query("DROP TABLE source_relationship"));
    }

    /**
     * This test checks that while deleting source we should get exception that some targets are still "active"
     *
     * @throws Exception
     * @throws ReflectionException
     * @return void
     */
    public function testRelationshipRestrict()
    {
        if(!$this->enabled) {
            $this->assertTrue(true);
            return;
        }

        // Setting up database
        $schema = $this->entityManager->schema(new SourceRelationship());
        $this->assertNotFalse($this->connection->query($schema['query']));

        $schema2 = $this->entityManager->schema(new TargetRelationshipRestrict());
        $this->assertNotFalse($this->connection->query($schema2['query']));

        // Adding new source relationship
        $sourceRelationship = new SourceRelationship();
        $sourceRelationship->setUsername("Hello World!");

        $this->entityManager->persist($sourceRelationship);

        // Adding new target relationship
        $targetRelationshipRestrict = new TargetRelationshipRestrict();
        $targetRelationshipRestrict->setUserId(1);
        $targetRelationshipRestrict->setText("Hello World!");

        $this->entityManager->persist($targetRelationshipRestrict);

        $repositorySource = new Repository(SourceRelationship::class, $this->connection);
        $repositoryTarget = new Repository(TargetRelationshipRestrict::class, $this->connection);

        // Removing result from source table should throw exception because have active target results
        $this->expectException(PDOException::class);
        $repositorySource->remove([
            'id' => 1
        ]);

        // Removing tables
        $this->assertNotFalse($this->connection->query("DROP TABLE target_relationship_restrict"));
        $this->assertNotFalse($this->connection->query("DROP TABLE source_relationship"));
    }
}
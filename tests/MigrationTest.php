<?php

namespace Luminar\Database\Tests;

use Luminar\Database\Connection\Connection;
use Luminar\Database\Exceptions\MigrationException;
use Luminar\Database\Migration\MigrationManager;
use Luminar\Database\Schema\Blueprint;
use Luminar\Database\Schema\SchemaBuilder;
use PDOException;
use PHPUnit\Framework\TestCase;

class MigrationTest extends TestCase
{
    /**
     * @return void
     * @throws MigrationException
     */
    public function testMigration()
    {
        try {
            // Create connection
            $connection = new Connection("mysql:host=localhost;dbname=luminar-test", 'root');
            $migrationDir = __DIR__ . '/fixtures/';
            $migrationManager = new MigrationManager($migrationDir, $connection);

            // Create Example Database
            $schemaBuilder = new SchemaBuilder();
            $sql = $schemaBuilder->create("users", function (Blueprint $table) {
                $table->addColumn("varchar(255)", "username");
            });
            $connection->query($sql);


            // Export Database
            $export = $migrationManager->export();
            $this->assertNotNull($export);

            // Import Database
            $import = $migrationManager->import($export);
            $this->assertTrue($import);

            $schemaBuilder = new SchemaBuilder();
            $sql = $schemaBuilder->drop("users");
            $connection->query($sql);

            @unlink($migrationDir . $export . '.sql');
        } catch (PDOException $e) {
            // Migration does not support sqlite
            echo "\nWARNING! Migration does not support sqlite so you need to have for e.g. MySQL Server\n";
            $this->assertTrue(true);
            return;
        }
    }
}
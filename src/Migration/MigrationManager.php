<?php

namespace Luminar\Database\Migration;

use Luminar\Database\Connection\Connection;
use Luminar\Database\Exceptions\MigrationException;
use PDO;

class MigrationManager
{
    /**
     * @var string $dir
     */
    protected string $dir;

    /**
     * @var Connection $connection
     */
    protected Connection $connection;

    /**
     * @param string $dir
     * @param Connection $connection
     * @throws MigrationException
     */
    public function __construct(string $dir, Connection $connection)
    {
        if(!is_dir($dir)) {
            throw new MigrationException("Directory does not exists!");
        }
        $this->connection = $connection;
        $this->dir = $dir;
    }

    /**
     * @param string $name
     * @return true
     * @throws MigrationException
     */
    public function import(string $name): true
    {
        $migrationDir = $this->dir . $name . '.sql';
        if(!file_exists($migrationDir)) {
            throw new MigrationException("Migration does not exist!");
        }

        $handle = fopen($migrationDir, "r+");
        $content = fread($handle, filesize($migrationDir));
        $contents = str_replace("\n", "", $content);
        $sql = explode(";", $contents);
        array_pop($sql);
        foreach ($sql as $query) {
            $this->connection->query($query);
        }

        return true;
    }

    /**
     * @return string
     */
    public function export(): string
    {
        $tables = $this->connection->query("SHOW TABLES");
        $tables = $tables->fetchAll(PDO::FETCH_ASSOC)[0];
        $output = '';
        foreach($tables as $table) {
            $result = $this->connection->query("SELECT * FROM " . $table);
            $numFields = $result->rowCount();

            $output .= "DROP TABLE IF EXISTS $table;";
            $row = $this->connection->query("SHOW CREATE TABLE $table");
            $row = $row->fetch(PDO::FETCH_NUM);
            $output .= "\n\n" . $row[1] . ";\n\n";

            for($i = 0; $i < $numFields; $i++) {
                $output .= "INSERT INTO $table VALUES (";
                for ($j = 0; $j < $numFields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    if($row[$j]) {
                        $output .= '"' . $row[$j] . '"';
                    } else {
                        $output .= '""';
                    }
                    if($j < $numFields - 1) {
                        $output .= ",";
                    }
                }
                $output .= ");\n";
            }
            $output .= "\n";
        }
        $id = rand(11111, 99999);
        $fileName = "migrate-" . $id . ".sql";
        $handle = file_put_contents($this->dir . DIRECTORY_SEPARATOR . $fileName, $output);
        return "migrate-$id";
    }
}
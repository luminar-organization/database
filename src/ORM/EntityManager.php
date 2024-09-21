<?php

namespace Luminar\Database\ORM;

use DateTime;
use Exception;
use Luminar\Database\Connection\Connection;
use Luminar\Database\ORM\Types\Timestamp;
use PDOStatement;
use ReflectionClass;
use ReflectionNamedType;

class EntityManager
{
    /**
     * @var Connection $connection
     */
    protected Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ReflectionNamedType $type
     * @param string $propertyName
     * @return string
     * @throws Exception
     */
    private function mapType(ReflectionNamedType $type, string $propertyName, int $length): string
    {
        switch($type->getName()) {
            case 'int':
                if($propertyName === 'id') {
                    return "INT AUTO_INCREMENT PRIMARY KEY";
                }
                return "INT ($length)";
            case 'string':
                return "VARCHAR($length)";
            case '?int':
                return "INT($length) NULL";
            case '?string':
                return "VARCHAR($length) NULL";
            case 'bool':
                return "BOOL";
            case "?bool":
                return "BIT NULL";
            case DateTime::class:
                return "DATETIME";
            case 'float':
                return "FLOAT";
            case '?float':
                return "FLOAT NULL";
            case 'array':
                return "LONGTEXT";
            default:
                throw new Exception("Unsupported type " . $type->getName());
        }
    }

    /**
     * @param object $entity
     * @return array
     * @throws Exception
     */
    public function schema(object $entity): array
    {
        $reflectionClass = new ReflectionClass($entity);
        $entityAttributes = $reflectionClass->getAttributes(Entity::class);
        if(empty($entityAttributes)) throw new Exception("Entity is missing Entity attribute");
        $tableName = $entityAttributes[0]->newInstance()->name;
        $sql = "CREATE TABLE $tableName (";
        $properties = $reflectionClass->getProperties();
        foreach($properties as $property) {
            $columnAttributes = $property->getAttributes(Column::class)[0];
            $columnName = $columnAttributes->newInstance()->name;
            $length = $columnAttributes->newInstance()->length;
            $columnType = $this->mapType($property->getType(), $property->getName(), ($length));
            if(!empty($columnAttributes)) $sql .= "$columnName $columnType, ";
        }

        $sql = rtrim($sql, ", ");
        $sql .= ")";
        return [
            'table' => $tableName,
            'query' => $sql
        ];
    }

    /**
     * @param object $entity
     * @return false|PDOStatement
     * @throws Exception
     */
    public function persist(object $entity): false|PDOStatement
    {
        $reflectionClass = new ReflectionClass($entity);
        $properties = $reflectionClass->getProperties();
        $tableName = $reflectionClass->getAttributes(Entity::class)[0]->newInstance()->name;
        $idValue = null;

        foreach ($properties as $property) {
            $attributes = $property->getAttributes(Column::class);
            if (!empty($attributes)) {
                $columnName = $attributes[0]->newInstance()->name;
                if ($columnName == 'id') {
                    if($property->isInitialized($entity)) {
                        $idValue = $property->getValue($entity);
                    }
                }
            }
        }

        if (!$tableName) {
            throw new Exception("Entity is missing Entity attribute");
        }

        $fields = [];
        $values = [];
        $updateFields = [];

        foreach ($properties as $property) {
            $attributes = $property->getAttributes(Column::class);
            if (!empty($attributes)) {
                $columnName = $attributes[0]->newInstance()->name;
                if ($columnName != 'id') {
                    $columnValue = $property->getValue($entity);
                    $fields[] = $columnName;
                    if (gettype($columnValue) == 'array') {
                        $columnValue = json_encode($columnValue);
                    }
                    $values[] = $this->quote($columnValue);
                    $updateFields[] = "$columnName = " . $this->quote($columnValue);
                }
            }
        }

        if ($idValue) {
            $updateFields = implode(", ", $updateFields);
            $sql = "UPDATE $tableName SET $updateFields WHERE id = " . $this->quote($idValue);
        } else {
            $fields = implode(", ", $fields);
            $values = implode(", ", $values);
            $sql = "INSERT INTO $tableName ($fields) VALUES ($values)";
        }

        return $this->connection->query($sql);
    }

    /**
     * @param string $value
     * @return string
     */
    private function quote(string $value): string {
        return "'" . addslashes($value) . "'";
    }
}
<?php

namespace Luminar\Database\ORM;

use DateTime;
use Exception;
use Luminar\Database\Connection\Connection;
use PDO;
use ReflectionClass;
use ReflectionException;

class Repository
{
    /**
     * @var string $tableName
     */
    protected string $tableName;

    /**
     * @var string $entityObject
     */
    private string $entityObject;

    /**
     * @var Connection $connection
     */
    private Connection $connection;

    /**
     * @param string $entity
     * @param Connection $connection
     * @throws ReflectionException
     * @throws Exception
     */
    public function __construct(string $entity, Connection $connection)
    {
        $this->connection = $connection;
        $this->entityObject = $entity;
        $reflectionClass = new ReflectionClass($entity);
        $entityAttributes = $reflectionClass->getAttributes(Entity::class);
        if(empty($entityAttributes)) throw new Exception("Entity is missing Entity attribute");
        $this->tableName = $entityAttributes[0]->newInstance()->name;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getAll(): array
    {
        $query = "SELECT * FROM {$this->tableName}";
        $result = $this->connection->query($query);
        $result = $result->fetchAll(PDO::FETCH_ASSOC);

        $entities = [];
        foreach($result as $entity) {
            $entityInstance = new $this->entityObject();
            foreach($entity as $key => $value) {
                $name = "set" . ucfirst($key);
                if($this->isJson($value)) {
                    $entityInstance->$name(json_decode($value, true));
                } elseif($this->isDate($value)) {
                    $entityInstance->$name(new DateTime($value));
                } else {
                    $entityInstance->$name($value);
                }
            }
            $entities[] = $entityInstance;
        }

        return $entities;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function remove(array $data): bool
    {
        $query = "DELETE FROM {$this->tableName}";
        $count = 0;
        $total = count($data);
        foreach($data as $key => $value) {
            if($count == 0) {
                $query .= " WHERE ";
            }
            $count += 1;
            if($count == $total) {
                $query .= sprintf("%s = '%s';", $key, $value);
            } else {
                $query .= sprintf("%s = '%s' AND ", $key, $value);
            }
        }

        $result = $this->connection->query($query);
        if($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array $data
     * @return array|bool
     * @throws Exception
     */
    public function findMany(array $data): array|bool
    {
        $query = "SELECT * FROM " . $this->tableName . " WHERE ";
        $count = 0;
        $total = count($data);
        foreach($data as $key => $value)
        {
            $count += 1;
            if($count == $total) {
                $query .= sprintf("%s = '%s';", $key, $value);
            } else {
                $query .= sprintf("%s = '%s' AND ", $key, $value);
            }
        }

        $result = $this->connection->query($query);
        $result = $result->fetchAll(PDO::FETCH_ASSOC);
        if(count($result) == 0 or !$result) {
            return false;
        }

        $response = [];

        foreach($result as $entityKey => $entityValue) {
            $response[$entityKey] = new $this->entityObject();
            foreach($entityValue as $key => $value) {
                $name = "set" . ucfirst($key);
                if($this->isJson($value)) {
                    $response[$entityKey]->$name(json_decode($value, true));
                } elseif($this->isDate($value)) {
                    $response[$entityKey]->$name(new DateTime($value));
                } else {
                    $response[$entityKey]->$name($value);
                }
            }
        }
        return $response;
    }

    /**
     * @param array $data
     * @return array|false
     * @throws Exception
     */
    public function findBy(array $data): object|bool
    {
        $query = "SELECT * FROM " . $this->tableName . " WHERE ";
        $count = 0;
        $total = count($data);
        foreach($data as $key => $value)
        {
            $count += 1;
            if($count == $total) {
                $query .= sprintf("%s = '%s';", $key, $value);
            } else {
                $query .= sprintf("%s = '%s' AND ", $key, $value);
            }
        }
        $result = $this->connection->query($query);
        $result = $result->fetchAll(PDO::FETCH_ASSOC);
        if(count($result) == 0 or !$result or @!$result[0]) {
            return false;
        }

        $entityInstance = new $this->entityObject();
        foreach($result as $entity) {
            foreach($entity as $key => $value) {
                $name = "set" . ucfirst($key);
                if($this->isJson($value)) {
                    $entityInstance->$name(json_decode($value, true));
                } elseif($this->isDate($value)) {
                    $entityInstance->$name(new DateTime($value));
                } else {
                    $entityInstance->$name($value);
                }
            }
        }
        return $entityInstance;
    }

    /**
     * @param string $value
     * @return bool
     */
    protected function isDate(string $value): bool
    {
        try {
            new DateTime($value);
            return true;
        } catch(Exception $exception) {
            return false;
        }
    }

    /**
     * @param string $value
     * @return bool
     */
    protected  function isJson(string $value): bool
    {
        return json_decode($value, true) !== null;
    }
}
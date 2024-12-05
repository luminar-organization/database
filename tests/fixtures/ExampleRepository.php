<?php

namespace Luminar\Database\Tests\fixtures;

use Luminar\Database\ORM\Repository;

class ExampleRepository extends Repository
{
    public function getWithId(int $id): array|bool|object
    {
        return $this->findBy(["id" => $id]);
    }
}
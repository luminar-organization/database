<?php

namespace Luminar\Database\ORM;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Column
{
    public function __construct(public string $name, public string $type = "native", public ?string $default = null, public int $length = 10, public ?DatabaseRelationship $databaseRelationship = null)
    {
    }
}
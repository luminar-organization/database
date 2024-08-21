<?php

namespace Luminar\Database\ORM;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Entity
{
    public function __construct(public string $name)
    {
    }
}
<?php

namespace Luminar\Database\ORM;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Column
{
    public function __construct(public string $name)
    {
    }
}
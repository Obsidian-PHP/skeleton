<?php

namespace Core\Http;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Register {
    public function __construct(private string $name, private mixed $class)
    {

    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClass(): mixed
    {
        return $this->class;
    }
}
<?php

namespace Core\Database\Migration;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Migrations\Migration as LaravelMigration;
use Illuminate\Database\Schema\Builder;

class Migration extends LaravelMigration
{
    protected Manager $manager;
    protected Builder $schema;

    public function __construct()
    {
        $this->manager = new Manager;
        $this->schema = $this->manager::schema();
    }
}
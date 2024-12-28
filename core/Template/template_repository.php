<?php namespace App\Domain\{{entityName}};

use Core\Http\Register;
use Core\Repository;
use Illuminate\Support\Collection;

#[Register("{{propertyName}}", {{className}}::class)]
class {{className}} extends Repository
{
    public function getAll(): Collection
    {
        return $this->table("{{tableName}}")
            ->get();
    }

    public function getSingle(int $id): object
    {
        return $this->table("{{tableName}}")
            ->where("id", $id)
            ->first();
    }
}
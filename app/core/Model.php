<?php

namespace App\Core;

use App\Core\Database;
use App\Utils\Query;

abstract class Model
{
    public array $data;
    protected Query $query;

    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->query = new Query(Database::getInstance());
    }

    public function all(): array
    {
        return $this->query->getAll();
    }

    public function get(int $id): mixed
    {
        return $this->query->getById($id);
    }

    public function validate(): bool
    {
        return true;
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        if (isset($this->data["id"]) && (self::get($this->data["id"]) !== null)) {
            return $this->query->update($this->data);
        } else {
            return $this->query->insert($this->data);
        }
    }

    public function delete(): bool
    {
        return $this->query->delete($this->data["id"]);
    }
}

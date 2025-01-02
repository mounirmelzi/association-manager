<?php

namespace App\Core;

use PDOException;
use App\Core\Database;
use App\Utils\Logger;
use App\Utils\Query;
use App\Utils\PDOExceptionParser;

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

    public function validate(): array
    {
        return [];
    }

    public function save(): array
    {
        $errors = $this->validate();
        if (!empty($errors)) {
            return $errors;
        }

        try {
            if (isset($this->data["id"]) && (self::get($this->data["id"]) !== null)) {
                $this->query->update($this->data);
            } else {
                $this->query->insert($this->data);
            }

            return [];
        } catch (PDOException $exception) {
            Logger::error($exception->getMessage());
            return PDOExceptionParser::toErrorArray($exception);
        }
    }

    public function delete(): bool
    {
        return $this->query->delete($this->data["id"]);
    }
}

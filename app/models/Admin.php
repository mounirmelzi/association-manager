<?php

namespace App\Models;

use PDOException;
use App\Utils\Logger;
use App\Utils\PDOExceptionParser;

class Admin extends User
{
    #[\Override]
    public function all(int $limit = 0, int $offset = 0): array
    {
        $this->query->setTable("admins JOIN users USING(id)");
        return $this->query->getAll($limit, $offset);
    }

    #[\Override]
    public function get(int $id): mixed
    {
        $this->query->setTable("admins JOIN users USING(id)");
        return $this->query->getById($id);
    }

    #[\Override]
    public function validate(): array
    {
        $requiredFields = [
            "username",
            "email",
            "first_name",
            "last_name",
            "phone",
            "password"
        ];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (!isset($this->data[$field])) {
                $errors[$field] = "$field is required";
            }
        }

        return $errors;
    }

    #[\Override]
    public function save(): array
    {
        $errors = $this->validate();
        if (!empty($errors)) {
            return $errors;
        }

        $this->query->database->getConnection()->beginTransaction();

        try {
            $userData = [
                "username" => $this->data["username"],
                "email" => $this->data["email"],
                "phone" => $this->data["phone"],
                "password" => $this->data["password"],
            ];

            if (isset($this->data["id"]) && self::get($this->data["id"]) !== null) {
                $this->query->setTable("users");
                $this->query->update(array_merge($userData, ["id" => $this->data["id"]]));
            } else {
                $this->query->setTable("users");
                $this->query->insert($userData);
                $user = $this->query->where(["username" => $this->data["username"]])[0];
                $this->data["id"] = $user["id"];
            }

            $adminData = [
                "id" => $this->data["id"],
                "first_name" => $this->data["first_name"],
                "last_name" => $this->data["last_name"],
            ];

            if (self::get($this->data["id"]) !== null) {
                $this->query->setTable("admins");
                $this->query->update($adminData);
            } else {
                $this->query->setTable("admins");
                $this->query->insert($adminData);
            }

            $this->query->database->getConnection()->commit();
            return [];
        } catch (PDOException $exception) {
            $this->query->database->getConnection()->rollback();
            Logger::error($exception->getMessage());
            return PDOExceptionParser::toErrorArray($exception);
        }
    }
}

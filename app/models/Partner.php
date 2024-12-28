<?php

namespace App\Models;

use Exception;
use App\Utils\Logger;

class Partner extends User
{
    #[\Override]
    public function all(): array
    {
        $this->query->setTable("partners JOIN users USING(id)");
        return $this->query->getAll();
    }

    #[\Override]
    public function get(int $id): mixed
    {
        $this->query->setTable("partners JOIN users USING(id)");
        return $this->query->getById($id);
    }

    #[\Override]
    public function validate(): bool
    {
        $requiredFields = [
            "username",
            "email",
            "name",
            "description",
            "category",
            "address",
            "phone",
            "password"
        ];

        foreach ($requiredFields as $field) {
            if (!isset($this->data[$field])) {
                return false;
            }
        }

        return true;
    }

    #[\Override]
    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
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

            $partnerData = [
                "id" => $this->data["id"],
                "name" => $this->data["name"],
                "description" => $this->data["description"],
                "category" => $this->data["category"],
                "address" => $this->data["address"],
            ];

            if (self::get($this->data["id"]) !== null) {
                $this->query->setTable("partners");
                $this->query->update($partnerData);
            } else {
                $this->query->setTable("partners");
                $this->query->insert($partnerData);
            }

            $this->query->database->getConnection()->commit();
            return true;
        } catch (Exception $e) {
            $this->query->database->getConnection()->rollback();
            Logger::error($e->getMessage());
            return false;
        }
    }
}

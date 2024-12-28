<?php

namespace App\Models;

use App\Core\Model;

abstract class User extends Model
{
    #[\Override]
    public function delete(): bool
    {
        $this->query->setTable("users");
        return $this->query->delete($this->data["id"]);
    }
}

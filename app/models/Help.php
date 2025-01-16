<?php

namespace App\Models;

use App\Core\Model;

class Help extends Model
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->query->setTable('helps');
    }

    public function getByUserId(int $user_id): array
    {
        return $this->query->where(["user_id" => $user_id]);
    }

    public function accept(): void
    {
        $this->data['is_valid'] = true;
        $this->save();
    }

    public function refuse(): void
    {
        $this->data['is_valid'] = false;
        $this->save();
    }
}

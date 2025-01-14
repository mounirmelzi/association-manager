<?php

namespace App\Models;

use App\Core\Model;

class Volunteering extends Model
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->query->setTable('volunteerings');
    }

    public function getByUserIdAndActivityId(int $user_id, int $activity_id): mixed
    {
        $data = $this->query->where(["user_id" => $user_id, "activity_id" => $activity_id]);
        return $data[0] ?? null;
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

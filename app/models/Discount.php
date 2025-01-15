<?php

namespace App\Models;

use App\Core\Model;
use App\Models\Partner;

class Discount extends Model
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->query->setTable("discounts");
    }

    public function getByUserIdWithPartner(int $user_id)
    {
        $partnerModel = new Partner();
        $discounts = $this->query->where(['user_id' => $user_id]);
        $discounts = array_map(function ($discount) use ($partnerModel) {
            $discount['partner'] = $partnerModel->get($discount['partner_id']);
            return $discount;
        }, $discounts);
        return $discounts;
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

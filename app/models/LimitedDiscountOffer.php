<?php

namespace App\Models;

use App\Core\Model;

class LimitedDiscountOffer extends Model
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->query->setTable("limited_discounts");
    }

    public function getByPartnerId(int $partner_id): array
    {
        return $this->query->where(["partner_id" => $partner_id]);
    }
}

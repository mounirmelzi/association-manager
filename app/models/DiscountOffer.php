<?php

namespace App\Models;

use App\Core\Model;

class DiscountOffer extends Model
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->query->setTable("discount_offers");
    }

    public function getByPartnerId(int $partner_id): array
    {
        return $this->query->where(["partner_id" => $partner_id]);
    }
}

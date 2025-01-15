<?php

namespace App\Models;

use App\Core\Model;

class Discount extends Model
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->query->setTable("discounts");
    }
}

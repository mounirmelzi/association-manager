<?php

namespace App\Models;

use App\Core\Model;

class CardType extends Model
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->query->setTable('card_types');
    }
}

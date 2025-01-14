<?php

namespace App\Models;

use App\Core\Model;
use App\Models\CardType;

class Card extends Model
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->query->setTable('cards');
    }

    public function getByUserIdWithType(int $user_id)
    {
        $cardTypeModel = new CardType();
        $cards = $this->query->where(['user_id' => $user_id]);
        $cards = array_map(function ($card) use ($cardTypeModel) {
            $card['type'] = $cardTypeModel->get($card['card_type_id']);
            return $card;
        }, $cards);
        return $cards;
    }
}

<?php

namespace App\Models;

use App\Core\Model;
use App\Models\Partner;
use App\Models\User;

class Favorite extends Model
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->query->setTable('users_favorite_partners');
    }

    public function getByUserIdAndPartnerId(int $user_id, int $partner_id)
    {
        $data = $this->query->where(['user_id' => $user_id, 'partner_id' => $partner_id]);
        return $data[0] ?? null;
    }

    public function getByUserIdWithPartner(int $user_id)
    {
        $partnerModel = new Partner();
        $favorites = $this->query->where(['user_id' => $user_id]);
        $favorites = array_map(function ($favorite) use ($partnerModel) {
            $favorite['partner'] = $partnerModel->get($favorite['partner_id']);
            return $favorite;
        }, $favorites);
        return $favorites;
    }

    public function getByCurrentUserWithPartner()
    {
        $user = User::current();
        return ($user !== null) ? $this->getByUserIdWithPartner($user['id']) : [];
    }
}

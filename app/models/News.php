<?php

namespace App\Models;

use App\Core\Model;

class News extends Model
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->query->setTable('news');
    }
}

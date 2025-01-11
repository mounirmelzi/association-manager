<?php

namespace App\Models;

use App\Core\Model;

class Navbar extends Model
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->query->setTable('navbar');
    }
}

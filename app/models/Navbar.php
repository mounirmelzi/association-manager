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

    #[\Override]
    public function all(int $limit = 0, int $offset = 0): array
    {
        return $this->query->getAll($limit, $offset, 'id ASC');
    }
}

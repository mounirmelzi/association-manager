<?php

namespace App\Core;

abstract class View
{
    protected array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    abstract public function renderHtml(): void;

    public function renderJson(): void
    {
        header('Content-Type: application/json');
        echo json_encode($this->data, JSON_PRETTY_PRINT);
    }
}

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Views\Pages\Home as HomePage;

class Home extends Controller
{
    public function index(): void
    {
        $home_page = new HomePage(["message" => "Hello, world!"]);
        $home_page->renderHtml();
    }
}

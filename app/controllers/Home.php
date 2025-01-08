<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Views\Pages\Home as HomePage;
use App\Views\Pages\Dashboard as DashboardPage;

class Home extends Controller
{
    public function index(): void
    {
        $page = new HomePage(["message" => "Hello, world!"]);
        $page->renderHtml();
    }

    public function dashboard(): void
    {
        $cards = [
            ["title" => "Members Managment", "link" => BASE_URL . "members", "icon" => "people"],
            ["title" => "Partners Managment", "link" => BASE_URL . "partners", "icon" => "people-fill"],
            ["title" => "Activities Managment", "link" => BASE_URL . "activities", "icon" => "activity"],
            ["title" => "Diaporama Managment", "link" => BASE_URL . "diaporama", "icon" => "file-earmark-slides"],
        ];

        $page = new DashboardPage(["cards" => $cards]);
        $page->renderHtml();
    }
}

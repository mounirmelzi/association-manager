<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Diaporama as DiaporamaModel;
use App\Views\Pages\Home as HomePage;
use App\Views\Pages\Dashboard as DashboardPage;

class Home extends Controller
{
    public function home(): void
    {
        $diaporamaModel = new DiaporamaModel();

        $slides = $diaporamaModel->all();

        $page = new HomePage([
            "slides" => $slides,
        ]);
        $page->renderHtml();
    }

    public function dashboard(): void
    {
        $cards = [
            ["title" => "Members Managment", "link" => BASE_URL . "members", "icon" => "people"],
            ["title" => "Partners Managment", "link" => BASE_URL . "partners", "icon" => "people-fill"],
            ["title" => "Card Types Managment", "link" => BASE_URL . "cards/types", "icon" => "person-vcard"],
            ["title" => "Help Types Managment", "link" => BASE_URL . "helps/types", "icon" => "person-raised-hand"],
            ["title" => "Activities Managment", "link" => BASE_URL . "activities", "icon" => "activity"],
            ["title" => "News Managment", "link" => BASE_URL . "news", "icon" => "newspaper"],
            ["title" => "Diaporama Managment", "link" => BASE_URL . "diaporama", "icon" => "file-earmark-slides"],
        ];

        $page = new DashboardPage(["cards" => $cards]);
        $page->renderHtml();
    }
}

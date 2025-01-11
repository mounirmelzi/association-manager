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
            [
                "title" => "Members Management",
                "link" => BASE_URL . "members",
                "icon" => "people"
            ],
            [
                "title" => "Partners Management",
                "link" => BASE_URL . "partners",
                "icon" => "people-fill"
            ],
            [
                "title" => "Discount Offers Management",
                "link" => BASE_URL . "discounts/offers",
                "icon" => "cart-dash"
            ],
            [
                "title" => "Limited Discount Offers Management",
                "link" => BASE_URL . "discounts/limited/offers",
                "icon" => "cart-dash-fill"
            ],
            [
                "title" => "Card Types Management",
                "link" => BASE_URL . "cards/types",
                "icon" => "person-vcard"
            ],
            [
                "title" => "Help Types Management",
                "link" => BASE_URL . "helps/types",
                "icon" => "person-raised-hand"
            ],
            [
                "title" => "Activities Management",
                "link" => BASE_URL . "activities",
                "icon" => "activity"
            ],
            [
                "title" => "News Management",
                "link" => BASE_URL . "news",
                "icon" => "newspaper"
            ],
            [
                "title" => "Diaporama Management",
                "link" => BASE_URL . "diaporama",
                "icon" => "file-earmark-slides"
            ],
            [
                "title" => "Navbar Management",
                "link" => BASE_URL . "navbar",
                "icon" => "window"
            ],
        ];

        $page = new DashboardPage(["cards" => $cards]);
        $page->renderHtml();
    }
}

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Partner;
use App\Views\Pages\PartnersList as PartnersListPage;

class Partners extends Controller
{
    public function index(): void
    {
        $model = new Partner();
        $page = new PartnersListPage(["partners" => $model->all()]);
        $page->renderHtml();
    }
}

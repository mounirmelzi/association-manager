<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Models\Partner;
use App\Models\User;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\PartnersList as PartnersListPage;
use App\Views\Pages\PartnerDetails as PartnerDetailsPage;

class Partners extends Controller
{
    public function index(): void
    {
        $model = new Partner();
        $page = new PartnersListPage(["partners" => $model->all()]);
        $page->renderHtml();
    }

    public function details(int $id): void
    {
        $user = User::current();
        if (($user["role"] === "partner") && ($user["id"] !== $id)) {
            $controller = new ErrorController();
            $controller->index(403, "Forbidden");
            return;
        }

        $model = new Partner();
        $partner = $model->get($id);
        if ($partner === null) {
            $controller = new ErrorController();
            $controller->index(404, "Partner not found");
            return;
        }

        $page = new PartnerDetailsPage(["partner" => $partner]);
        $page->renderHtml();
    }

    public function delete(int $id): void
    {
        $user = User::current();
        if (($user["role"] === "partner") && ($user["id"] !== $id)) {
            $controller = new ErrorController();
            $controller->index(403, "Forbidden");
            return;
        }

        $model = new Partner();
        $partner = $model->get($id);
        if ($partner === null) {
            $controller = new ErrorController();
            $controller->index(404, "Partner not found");
            return;
        }

        $partner = new Partner($partner);

        if (!$partner->delete()) {
            $controller = new ErrorController();
            $controller->index(500, "Cannot delete the partner account, try again later!");
            return;
        }

        if ($user["role"] === "admin") {
            App::redirect("/partners");
        } else {
            User::logout();
            App::redirect("/");
        }
    }
}

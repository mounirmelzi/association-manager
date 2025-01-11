<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Utils\Request;
use App\Models\Navbar as NavbarModel;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\Navbar as NavbarPage;

class Navbar extends Controller
{
    public function index(): void
    {
        if (Request::method() === "GET") {
            $model = new NavbarModel();
            $page = new NavbarPage(["items" => $model->all()]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $values = [
                'name' => Request::data('name'),
                'url' => Request::data('url'),
            ];

            $navbar = new NavbarModel($values);
            $errors = $navbar->save();

            if (!empty($errors)) {
                return;
            }

            App::redirect("/navbar");
        }
    }

    public function delete(int $id): void
    {
        $model = new NavbarModel();
        $navbar = $model->get($id);
        if ($navbar === null) {
            $controller = new ErrorController();
            $controller->index(404, "Navbar item not found");
            return;
        }

        $navbar = new NavbarModel($navbar);

        if (!$navbar->delete()) {
            $controller = new ErrorController();
            $controller->index(500, "Cannot delete the navbar item now, try again later!");
            return;
        }

        App::redirect("/navbar");
    }
}

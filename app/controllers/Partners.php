<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Utils\Request;
use App\Models\Partner;
use App\Models\User;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\PartnersList as PartnersListPage;
use App\Views\Pages\PartnerDetails as PartnerDetailsPage;
use App\Views\Pages\PartnersForm as PartnersFormPage;

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

    public function create(): void
    {
        if (Request::method() === "GET") {
            $page = new PartnersFormPage([
                'title' => 'Create Partner',
                'action' => 'create'
            ]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $errors = [];
            $values = [
                "name" => Request::data("name"),
                "description" => Request::data("description"),
                "category" => Request::data("category"),
                "address" => Request::data("address"),
                "email" => Request::data("email"),
                "phone" => Request::data("phone"),
                "username" => Request::data("password"),
                "password" => Request::data("password"),
                "confirm_password" => Request::data("confirm_password"),
            ];

            if ($values["password"] !== $values["confirm_password"]) {
                $errors["confirm_password"] = "password mismatch";
            }

            if (!empty($errors)) {
                $page = new PartnersFormPage([
                    'title' => 'Create Partner',
                    'action' => 'create',
                    "values" => $values,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            $partner = new Partner($values);
            $errors = $partner->save();

            if (!empty($errors)) {
                $page = new PartnersFormPage([
                    'title' => 'Create Partner',
                    'action' => 'create',
                    "values" => $values,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            App::redirect("/partners");
        }
    }

    public function edit(int $id): void
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

        if (Request::method() === "GET") {
            $page = new PartnersFormPage([
                'title' => 'Edit Partner',
                'action' => 'edit',
                "values" => $partner,
            ]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $errors = [];
            $values = [
                "old_password" => Request::data("old_password"),
                "new_password" => Request::data("new_password"),
                "confirm_password" => Request::data("confirm_password"),
            ];

            $partner['name'] =  Request::data("name");
            $partner['description'] = Request::data('description');
            $partner['category'] = Request::data('category');
            $partner['address'] = Request::data('address');
            $partner['email'] = Request::data('email');
            $partner['phone'] = Request::data('phone');

            if (($values["new_password"] !== "") || ($values["confirm_password"] !== "")) {
                if (($user["role"] === "partner") && ($partner["password"] !== $values["old_password"])) {
                    $errors["old_password"] = "wrong password";
                }

                if ($values["new_password"] !== $values["confirm_password"]) {
                    $errors["confirm_password"] = "password mismatch";
                }

                $partner["password"] = $values["new_password"];
            }

            if (!empty($errors)) {
                $page = new PartnersFormPage([
                    'title' => 'Edit Partner',
                    'action' => 'edit',
                    "values" => array_merge($partner, $values),
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            $partner = new Partner($partner);
            $errors = $partner->save();

            if (!empty($errors)) {
                $page = new PartnersFormPage([
                    'title' => 'Edit Partner',
                    'action' => 'edit',
                    "values" => array_merge($partner->data, $values),
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            App::redirect("/partners/$id");
        }
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

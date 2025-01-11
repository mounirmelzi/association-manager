<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Utils\Request;
use App\Utils\File;
use App\Models\Partner;
use App\Models\CardType;
use App\Models\DiscountOffer;
use App\Models\LimitedDiscountOffer;
use App\Models\User;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\PartnersList as PartnersListPage;
use App\Views\Pages\PartnerDetails as PartnerDetailsPage;
use App\Views\Pages\PartnerForm as PartnerFormPage;

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

        $cardTypeModel = new CardType();

        $discountOfferModel = new DiscountOffer();
        $discounts = array_map(function ($discount) use ($cardTypeModel) {
            $cardType = $cardTypeModel->get($discount['card_type_id']);
            $discount['card_type'] = $cardType['type'];
            return $discount;
        }, $discountOfferModel->getByPartnerId($id));

        $limitedDiscountOfferModel = new LimitedDiscountOffer();
        $limitedDiscounts = array_map(function ($discount) use ($cardTypeModel) {
            $cardType = $cardTypeModel->get($discount['card_type_id']);
            $discount['card_type'] = $cardType['type'];
            return $discount;
        }, $limitedDiscountOfferModel->getByPartnerId($id));

        $page = new PartnerDetailsPage([
            "partner" => $partner,
            "discounts" => $discounts,
            "limitedDiscounts" => $limitedDiscounts,
        ]);
        $page->renderHtml();
    }

    public function create(): void
    {
        if (Request::method() === "GET") {
            $page = new PartnerFormPage([
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

            if (Request::file("logo")) {
                $result = File::upload(
                    Request::file("logo"),
                    "uploads" . DIRECTORY_SEPARATOR . "partners",
                    ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                    ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                    5 * 1024 * 1024 // 5 MB in bytes
                );

                if ($result["success"]) {
                    $values["logo_url"] = $result["path"];
                } else {
                    $errors["logo"] = $result["message"];
                }
            }

            if ($values["password"] !== $values["confirm_password"]) {
                $errors["confirm_password"] = "password mismatch";
            }

            if (!empty($errors)) {
                if (isset($values["logo_url"])) {
                    File::delete($values["logo_url"]);
                    unset($values["logo_url"]);
                }

                $page = new PartnerFormPage([
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
                if (isset($values["logo_url"])) {
                    File::delete($values["logo_url"]);
                    unset($values["logo_url"]);
                }

                $page = new PartnerFormPage([
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
            $page = new PartnerFormPage([
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

            if (Request::file("logo")) {
                $result = File::upload(
                    Request::file("logo"),
                    "uploads" . DIRECTORY_SEPARATOR . "partners",
                    ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                    ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                    5 * 1024 * 1024 // 5 MB in bytes
                );

                if ($result["success"]) {
                    $old_logo_url = $partner["logo_url"];
                    $new_logo_url = $result["path"];
                } else {
                    $errors["logo"] = $result["message"];
                }
            }

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
                if (isset($new_logo_url)) {
                    File::delete($new_logo_url);
                    unset($new_logo_url);
                }

                $page = new PartnerFormPage([
                    'title' => 'Edit Partner',
                    'action' => 'edit',
                    "values" => array_merge($partner, $values),
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            if (isset($new_logo_url)) {
                $partner["logo_url"] = $new_logo_url;
            }

            $partner = new Partner($partner);
            $errors = $partner->save();

            if (!empty($errors)) {
                if (isset($new_logo_url)) {
                    File::delete($new_logo_url);
                    unset($new_logo_url);
                }

                $page = new PartnerFormPage([
                    'title' => 'Edit Partner',
                    'action' => 'edit',
                    "values" => array_merge($partner->data, $values),
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            if (isset($old_logo_url)) {
                File::delete($old_logo_url);
                unset($old_logo_url);
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
        $logo_url = $partner->data['logo_url'] ?? null;

        if (!$partner->delete()) {
            $controller = new ErrorController();
            $controller->index(500, "Cannot delete the partner account, try again later!");
            return;
        }

        if ($logo_url !== null) {
            File::delete($logo_url);
        }

        if ($user["role"] === "admin") {
            App::redirect("/partners");
        } else {
            User::logout();
            App::redirect("/");
        }
    }
}

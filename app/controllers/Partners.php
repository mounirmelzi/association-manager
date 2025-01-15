<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Utils\Request;
use App\Utils\File;
use App\Utils\QRCode;
use App\Models\User;
use App\Models\Partner;
use App\Models\Favorite;
use App\Models\Card;
use App\Models\CardType;
use App\Models\DiscountOffer;
use App\Models\LimitedDiscountOffer;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\PartnersList as PartnersListPage;
use App\Views\Pages\UserPartnersList as UserPartnersListPage;
use App\Views\Pages\PartnerDetails as PartnerDetailsPage;
use App\Views\Pages\PartnerForm as PartnerFormPage;
use DateTime;

class Partners extends Controller
{
    public function index(): void
    {
        $model = new Partner();
        $user = User::current();

        if (($user !== null) && ($user['role'] === 'admin')) {
            $page = new PartnersListPage(['partners' => $model->all()]);
        } else {
            $favoriteModel = new Favorite();
            $favoritePartners = $favoriteModel->getByCurrentUserWithPartner();
            $favoritePartners = array_map(function ($favoritePartner) {
                return $favoritePartner['partner'];
            }, $favoritePartners);
            $page = new UserPartnersListPage([
                'partners' => $model->all(),
                'favoritePartners' => $favoritePartners
            ]);
        }

        $page->renderHtml();
    }

    public function details(int $id): void
    {
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

        $cardModel = new Card();
        $cards = $cardModel->getByUserIdWithType($id);

        $isFavorite = false;
        $currentUser = User::current();
        if ($currentUser !== null) {
            $favoriteModel = new Favorite();
            $favorite = $favoriteModel->getByUserIdAndPartnerId($currentUser['id'], $partner['id']);
            if ($favorite !== null) {
                $isFavorite = true;
            }
        }

        $page = new PartnerDetailsPage([
            "partner" => $partner,
            "discounts" => $discounts,
            "limitedDiscounts" => $limitedDiscounts,
            "cards" => $cards,
            "isFavorite" => $isFavorite,
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

    public function createCard(int $id): void
    {
        $model = new Partner();
        $partner = $model->get($id);
        if ($partner === null) {
            $controller = new ErrorController();
            $controller->index(404, "Partner not found");
            return;
        }

        $expirationDate = (new DateTime())->modify('+1 year')->format('Y-m-d H:i:s');
        $qrCodePath = 'uploads' . DIRECTORY_SEPARATOR . 'cards' . DIRECTORY_SEPARATOR . 'file' . uniqid() . '.png';

        $values = [
            'user_id' => $id,
            'card_type_id' => Request::data('card_type_id'),
            'qrcode_image_url' => $qrCodePath,
            'expiration_date' => $expirationDate,
        ];

        $qrCodeData = [
            'user_role' => 'partner',
            'user_id' => $values['user_id'],
            'card_type_id' => $values['card_type_id'],
            'expiration_date' => $values['expiration_date'],
        ];

        $qrCodeData = json_encode($qrCodeData, JSON_PRETTY_PRINT);
        QRCode::generate($qrCodeData, $qrCodePath);

        $card = new Card($values);
        $errors = $card->save();

        if (!empty($errors)) {
            File::delete($qrCodePath);
            return;
        }

        App::redirect("/partners/$id");
    }

    public function favorite(int $id): void
    {
        $model = new Partner();
        $partner = $model->get($id);
        if ($partner === null) {
            $controller = new ErrorController();
            $controller->index(404, "Partner not found");
            return;
        }

        $user = User::current();

        $favoriteModel = new Favorite();
        $favorite = $favoriteModel->getByUserIdAndPartnerId($user['id'], $partner['id']);

        if ($favorite === null) {
            $favorite = new Favorite([
                'user_id' => $user['id'],
                'partner_id' => $partner['id']
            ]);
            $favorite->save();
        } else {
            $favorite = new Favorite($favorite);
            $favorite->delete();
        }

        App::redirect("/partners/$id");
    }
}

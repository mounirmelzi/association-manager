<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Core\Database;
use App\Utils\Request;
use App\Utils\Query;
use App\Models\User;
use App\Models\Discount;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\DiscountForm as DiscountFormPage;

class Discounts extends Controller
{
    public function create(int $user_id): void
    {
        $query = new Query(Database::getInstance());
        $query->setTable('users');
        $user = $query->getById($user_id);
        if ($user === null) {
            $errorController = new ErrorController();
            $errorController->index(404, "User not found");
            return;
        }

        if (Request::method() === "GET") {
            $page = new DiscountFormPage();
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $partner = User::current();
            $values = [
                'partner_id' => $partner['id'],
                'user_id' => $user['id'],
                'amount' => Request::data('amount'),
                'description' => Request::data('description'),
            ];

            $discount = new Discount($values);
            $errors = $discount->save();

            if (!empty($errors)) {
                $page = new DiscountFormPage(["values" => $values, "errors" => $errors]);
                $page->renderHtml();
                return;
            }

            App::redirect("/scanner");
        }
    }

    public function validation(int $id): void
    {
        $discountModel = new Discount();
        $discount = $discountModel->get($id);
        if ($discount === null) {
            $controller = new ErrorController();
            $controller->index(404, "Discount not found");
            return;
        }

        $discount = new Discount($discount);
        if ($discount->data['is_valid']) {
            $discount->refuse();
        } else {
            $discount->accept();
        }

        $redirectUrl = Request::data('redirect', '/home');
        App::redirect($redirectUrl);
    }
}

<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Core\Database;
use App\Utils\Query;
use App\Utils\Request;
use App\Utils\File;
use App\Models\User;
use App\Models\Payment;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\PaymentsList as PaymentsListPage;
use App\Views\Pages\PaymentForm as PaymentFormPage;

class Payments extends Controller
{
    public function index(): void
    {
        $paymentModel = new Payment();
        $user = User::current();
        $query = new Query(Database::getInstance());
        $query->setTable('users');
        $payments = ($user['role'] === 'admin') ? $paymentModel->all() : $paymentModel->getByUserId($user['id']);
        $payments = array_map(function ($payment) use ($query) {
            $payer = $query->getById($payment['user_id']);
            $payerRole = User::getRoleById($payment['user_id']);
            $payment['user_email'] = $payer['email'];
            $payment['user_role'] = $payerRole;
            return $payment;
        }, $payments);
        $page = new PaymentsListPage(['payments' => $payments]);
        $page->renderHtml();
    }

    public function create(): void
    {
        if (Request::method() === "GET") {
            $page = new PaymentFormPage();
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $user = User::current();

            $errors = [];
            $values = [
                'user_id' => $user['id'],
                'type' => Request::data('type'),
                'amount' => Request::data('amount'),
            ];

            if (Request::file("receipt_image")) {
                $result = File::upload(
                    Request::file("receipt_image"),
                    "uploads" . DIRECTORY_SEPARATOR . "payments",
                    ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                    ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                    5 * 1024 * 1024 // 5 MB in bytes
                );

                if ($result["success"]) {
                    $values["receipt_image_url"] = $result["path"];
                } else {
                    $errors["receipt_image"] = $result["message"];
                }
            }

            if (!empty($errors)) {
                if (isset($values["receipt_image_url"])) {
                    File::delete($values["receipt_image_url"]);
                    unset($values["receipt_image_url"]);
                }

                $page = new PaymentFormPage(["values" => $values, "errors" => $errors]);
                $page->renderHtml();
                return;
            }

            $payment = new Payment($values);
            $errors = $payment->save();

            if (!empty($errors)) {
                if (isset($values["receipt_image_url"])) {
                    File::delete($values["receipt_image_url"]);
                    unset($values["receipt_image_url"]);
                }

                $page = new PaymentFormPage(["values" => $values, "errors" => $errors]);
                $page->renderHtml();
                return;
            }

            App::redirect("/payments");
        }
    }

    public function validation(int $id): void
    {
        $model = new Payment();
        $payment = $model->get($id);
        if ($payment === null) {
            $controller = new ErrorController();
            $controller->index(404, "Payment not found");
            return;
        }

        $payment = new Payment($payment);
        if ($payment->data['is_valid']) {
            $payment->refuse();
        } else {
            $payment->accept();
        }

        App::redirect("/payments");
    }

    public function delete(int $id): void
    {
        $model = new Payment();
        $payment = $model->get($id);
        if ($payment === null) {
            $controller = new ErrorController();
            $controller->index(404, "Payment not found");
            return;
        }

        $payment = new Payment($payment);
        $receipt_image_url = $payment->data['receipt_image_url'] ?? null;

        if (!$payment->delete()) {
            $controller = new ErrorController();
            $controller->index(500, "Cannot delete the payment now, try again later!");
            return;
        }

        if ($receipt_image_url !== null) {
            File::delete($receipt_image_url);
        }

        App::redirect("/payments");
    }
}

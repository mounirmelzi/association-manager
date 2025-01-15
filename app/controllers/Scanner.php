<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Core\Database;
use App\Utils\Request;
use App\Utils\Query;
use App\Models\CardType;
use App\Models\Card;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\Scanner as ScannerPage;

class Scanner extends Controller
{
    public function index(): void
    {
        $cardTypeModel = new CardType();

        if (Request::method() === "GET") {
            $page = new ScannerPage(['cardTypes' => $cardTypeModel->all()]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $values = [
                'card_type_id' => Request::data('card_type_id'),
                'username' => Request::data('username'),
            ];

            $query = new Query(Database::getInstance());
            $query->setTable('users');
            $response = $query->where(['username' => $values['username']]);
            $user = $response[0] ?? null;

            if ($user === null) {
                $page = new ScannerPage([
                    'cardTypes' => $cardTypeModel->all(),
                    'values' => $values,
                    'errors' => ['username' => 'User not found'],
                ]);
                $page->renderHtml();
                return;
            }

            $cardModel = new Card();
            $userCards = $cardModel->getByUserIdWithType($user['id']);

            $userCards = array_filter($userCards, function ($userCard) use ($values) {
                if ($userCard['card_type_id'] != $values['card_type_id']) {
                    return false;
                }

                $cardExpirationDate = $userCard['expiration_date'];
                $isExpired = strtotime($cardExpirationDate) < time();
                if ($isExpired) {
                    return false;
                }

                return true;
            });

            $card = $userCards[0] ?? null;

            if ($card === null) {
                $page = new ScannerPage([
                    'cardTypes' => $cardTypeModel->all(),
                    'values' => $values,
                    'errors' => ['card_type_id' => 'No active card found with this type for the scanned user'],
                ]);
                $page->renderHtml();
                return;
            }

            $this->postScan($user, $card);
        }
    }

    public function qr(): void
    {
        $errorController = new ErrorController();

        $values = [
            'user_id' => Request::data('user_id'),
            'card_type_id' => Request::data('card_type_id'),
            'expiration_date' => Request::data('expiration_date'),
            'user_role' => Request::data('user_role'),
        ];

        foreach ($values as $key => $value) {
            if ($value === null) {
                $errorController->index(400, "Bad Request, Invalid QR Code");
                return;
            }
        }

        $query = new Query(Database::getInstance());

        $query->setTable('users');
        $response = $query->where(['id' => $values['user_id']]);
        $user = $response[0] ?? null;

        if ($user === null) {
            $errorController->index(404, "User not found");
            return;
        }

        $query->setTable('cards');
        $response = $query->where([
            'user_id' => $values['user_id'],
            'card_type_id' => $values['card_type_id'],
            'expiration_date' => $values['expiration_date'],
        ]);
        $card = $response[0] ?? null;

        if ($card === null) {
            $errorController->index(404, "This card either expired or deleted");
            return;
        }

        $this->postScan($user, $card);
    }

    private function postScan(array $user, array $card): void
    {
        var_dump($user);
        var_dump($card);
    }
}

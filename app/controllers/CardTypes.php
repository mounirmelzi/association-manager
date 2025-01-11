<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Utils\Request;
use App\Models\CardType;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\CardTypesList as CardTypesListPage;
use App\Views\Pages\CardTypesForm as CardTypesFormPage;

class CardTypes extends Controller
{
    public function index(): void
    {
        $model = new CardType();
        $page = new CardTypesListPage(['types' => $model->all()]);
        $page->renderHtml();
    }

    public function create(): void
    {
        if (Request::method() === "GET") {
            $page = new CardTypesFormPage([
                'title' => 'Create New Card Type',
            ]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $values = [
                "type" => Request::data("type"),
                "fee" => Request::data("fee"),
            ];

            $type = new CardType($values);
            $errors = $type->save();

            if (!empty($errors)) {
                $page = new CardTypesFormPage([
                    'title' => 'Create New Card Type',
                    "values" => $values,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            App::redirect("/cards/types");
        }
    }

    public function edit(int $id): void
    {
        $model = new CardType();
        $type = $model->get($id);
        if ($type === null) {
            $controller = new ErrorController();
            $controller->index(404, "Card type not found");
            return;
        }

        if (Request::method() === "GET") {
            $page = new CardTypesFormPage([
                'title' => 'Edit Card Type',
                "values" => $type,
            ]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $type['type'] =  Request::data("type");
            $type['fee'] = Request::data('fee');

            $type = new CardType($type);
            $errors = $type->save();

            if (!empty($errors)) {
                $page = new CardTypesFormPage([
                    'title' => 'Edit Card Type',
                    "values" => $type->data,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            App::redirect("/cards/types");
        }
    }

    public function delete(int $id): void
    {
        $model = new CardType();
        $type = $model->get($id);
        if ($type === null) {
            $controller = new ErrorController();
            $controller->index(404, "Card type not found");
            return;
        }

        $type = new CardType($type);
        if (!$type->delete()) {
            $controller = new ErrorController();
            $controller->index(500, "Cannot delete the card type now, try again later!");
            return;
        }

        App::redirect("/cards/types");
    }
}

<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Utils\Request;
use App\Models\HelpType;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\HelpTypesList as HelpTypesListPage;
use App\Views\Pages\HelpTypesForm as HelpTypesFormPage;

class HelpTypes extends Controller
{
    public function index(): void
    {
        $model = new HelpType();
        $page = new HelpTypesListPage(['types' => $model->all()]);
        $page->renderHtml();
    }

    public function create(): void
    {
        if (Request::method() === "GET") {
            $page = new HelpTypesFormPage([
                'title' => 'Create New Help Type',
                'action' => 'create',
            ]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $values = [
                "type" => Request::data("type"),
                "attachments_description" => Request::data("attachments_description"),
            ];

            $type = new HelpType($values);
            $errors = $type->save();

            if (!empty($errors)) {
                $page = new HelpTypesFormPage([
                    'title' => 'Create New Help Type',
                    'action' => 'create',
                    "values" => $values,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            App::redirect("/helps/types");
        }
    }

    public function edit(int $id): void
    {
        $model = new HelpType();
        $type = $model->get($id);
        if ($type === null) {
            $controller = new ErrorController();
            $controller->index(404, "Help type not found");
            return;
        }

        if (Request::method() === "GET") {
            $page = new HelpTypesFormPage([
                'title' => 'Edit Help Type',
                'action' => 'edit',
                "values" => $type,
            ]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $type['type'] =  Request::data("type");
            $type['attachments_description'] = Request::data('attachments_description');

            $type = new HelpType($type);
            $errors = $type->save();

            if (!empty($errors)) {
                $page = new HelpTypesFormPage([
                    'title' => 'Edit Help Type',
                    'action' => 'edit',
                    "values" => $type,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            App::redirect("/helps/types");
        }
    }

    public function delete(int $id): void
    {
        $model = new HelpType();
        $type = $model->get($id);
        if ($type === null) {
            $controller = new ErrorController();
            $controller->index(404, "Help type not found");
            return;
        }

        $type = new HelpType($type);
        if (!$type->delete()) {
            $controller = new ErrorController();
            $controller->index(500, "Cannot delete the help type now, try again later!");
            return;
        }

        App::redirect("/helps/types");
    }
}

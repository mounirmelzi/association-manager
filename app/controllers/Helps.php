<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Core\Database;
use App\Utils\Query;
use App\Utils\Request;
use App\Utils\File;
use App\Models\User;
use App\Models\Help;
use App\Models\HelpType;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\HelpsList as HelpsListPage;
use App\Views\Pages\HelpForm as HelpFormPage;

class Helps extends Controller
{
    public function index(): void
    {
        $helpModel = new Help();
        $helpTypeModel = new HelpType();
        $user = User::current();
        $query = new Query(Database::getInstance());
        $query->setTable('users');
        $helps = ($user['role'] === 'admin') ? $helpModel->all() : $helpModel->getByUserId($user['id']);
        $helps = array_map(function ($help) use ($query, $helpTypeModel) {
            $requester = $query->getById($help['user_id']);
            $requesterRole = User::getRoleById($help['user_id']);
            $helpType = $helpTypeModel->get($help['help_type_id']);
            $help['user_email'] = $requester['email'];
            $help['user_role'] = $requesterRole;
            $help['help_type'] = $helpType['type'];
            return $help;
        }, $helps);
        $page = new HelpsListPage(['helps' => $helps]);
        $page->renderHtml();
    }

    public function create(): void
    {
        $helpTypeModel = new HelpType();

        if (Request::method() === "GET") {
            $page = new HelpFormPage(['helpTypes' => $helpTypeModel->all()]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $user = User::current();

            $errors = [];
            $values = [
                'user_id' => $user['id'],
                'help_type_id' => Request::data('help_type_id'),
                'description' => Request::data('description'),
            ];

            if (Request::file("attachments")) {
                $result = File::upload(
                    file: Request::file("attachments"),
                    uploadDir: "uploads" . DIRECTORY_SEPARATOR . "helps",
                    maxSize: 20 * 1024 * 1024, // 20 MB in bytes
                    allowedExtensions: ['zip', 'rar'],
                    allowedTypes: [
                        'application/octet-stream',
                        'application/vnd.rar',
                        'application/x-rar-compressed',
                        'application/x-zip-compressed',
                        'application/zip',
                        'multipart/x-zip',
                    ],
                );

                if ($result["success"]) {
                    $values["attachments_url"] = $result["path"];
                } else {
                    $errors["attachments"] = $result["message"];
                }
            }

            if (!empty($errors)) {
                if (isset($values["attachments_url"])) {
                    File::delete($values["attachments_url"]);
                    unset($values["attachments_url"]);
                }

                $page = new HelpFormPage([
                    "values" => $values,
                    "errors" => $errors,
                    'helpTypes' => $helpTypeModel->all(),
                ]);
                $page->renderHtml();
                return;
            }

            $help = new Help($values);
            $errors = $help->save();

            if (!empty($errors)) {
                if (isset($values["attachments_url"])) {
                    File::delete($values["attachments_url"]);
                    unset($values["attachments_url"]);
                }

                $page = new HelpFormPage([
                    "values" => $values,
                    "errors" => $errors,
                    'helpTypes' => $helpTypeModel->all(),
                ]);
                $page->renderHtml();
                return;
            }

            App::redirect("/helps");
        }
    }

    public function validation(int $id): void
    {
        $model = new Help();
        $help = $model->get($id);
        if ($help === null) {
            $controller = new ErrorController();
            $controller->index(404, "Help request not found");
            return;
        }

        $help = new Help($help);
        if ($help->data['is_valid']) {
            $help->refuse();
        } else {
            $help->accept();
        }

        App::redirect("/helps");
    }

    public function delete(int $id): void
    {
        $model = new Help();
        $help = $model->get($id);
        if ($help === null) {
            $controller = new ErrorController();
            $controller->index(404, "Help request not found");
            return;
        }

        $help = new Help($help);
        $attachments_url = $help->data['attachments_url'] ?? null;

        if (!$help->delete()) {
            $controller = new ErrorController();
            $controller->index(500, "Cannot delete the help request now, try again later!");
            return;
        }

        if ($attachments_url !== null) {
            File::delete($attachments_url);
        }

        App::redirect("/helps");
    }
}

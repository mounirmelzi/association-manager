<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Utils\Request;
use App\Utils\File;
use App\Models\Diaporama as DiaporamaModel;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\Diaporama as DiaporamaPage;

class Diaporama extends Controller
{
    public function index(): void
    {
        if (Request::method() === "GET") {
            $model = new DiaporamaModel();
            $page = new DiaporamaPage(["slides" => $model->all()]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $errors = [];
            $values = [];

            if (Request::file("image")) {
                $result = File::upload(
                    Request::file("image"),
                    "uploads" . DIRECTORY_SEPARATOR . "diaporama",
                    ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                    ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                    5 * 1024 * 1024 // 5 MB in bytes
                );

                if ($result["success"]) {
                    $values["image_url"] = $result["path"];
                } else {
                    $errors["image"] = $result["message"];
                }
            }

            if (!empty($errors)) {
                if (isset($values["image_url"])) {
                    File::delete($values["image_url"]);
                    unset($values["image_url"]);
                }

                App::redirect("/diaporama");
            }

            $diaporama = new DiaporamaModel($values);
            $errors = $diaporama->save();

            if (!empty($errors)) {
                if (isset($values["image_url"])) {
                    File::delete($values["image_url"]);
                    unset($values["image_url"]);
                }
            }

            App::redirect("/diaporama");
        }
    }

    public function delete(int $id): void
    {
        $model = new DiaporamaModel();
        $diaporama = $model->get($id);
        if ($diaporama === null) {
            $controller = new ErrorController();
            $controller->index(404, "Diaporama slide not found");
            return;
        }

        $diaporama = new DiaporamaModel($diaporama);
        $image_url = $diaporama->data['image_url'] ?? null;

        if (!$diaporama->delete()) {
            $controller = new ErrorController();
            $controller->index(500, "Cannot delete the diaporama slide now, try again later!");
            return;
        }

        if ($image_url !== null) {
            File::delete($image_url);
        }

        App::redirect("/diaporama");
    }
}

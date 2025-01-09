<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Utils\Request;
use App\Utils\File;
use App\Models\News as NewsModel;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\NewsList as NewsListPage;
use App\Views\Pages\NewsDetails as NewsDetailsPage;
use App\Views\Pages\NewsForm as NewsFormPage;

class News extends Controller
{
    public function index(): void
    {
        $model = new NewsModel();
        $page = new NewsListPage(['news' => $model->all()]);
        $page->renderHtml();
    }

    public function details(int $id): void
    {
        $model = new NewsModel();
        $news = $model->get($id);
        if ($news === null) {
            $controller = new ErrorController();
            $controller->index(404, "News not found");
            return;
        }

        $page = new NewsDetailsPage(["news" => $news]);
        $page->renderHtml();
    }

    public function create(): void
    {
        if (Request::method() === "GET") {
            $page = new NewsFormPage([
                'title' => 'Create New News',
                'action' => 'create',
            ]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $errors = [];
            $values = [
                "title" => Request::data("title"),
                "description" => Request::data("description"),
            ];

            if (Request::file("image")) {
                $result = File::upload(
                    Request::file("image"),
                    "uploads" . DIRECTORY_SEPARATOR . "news",
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

                $page = new NewsFormPage([
                    'title' => 'Create New News',
                    'action' => 'create',
                    "values" => $values,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            $news = new NewsModel($values);
            $errors = $news->save();

            if (!empty($errors)) {
                if (isset($values["image_url"])) {
                    File::delete($values["image_url"]);
                    unset($values["image_url"]);
                }

                $page = new NewsFormPage([
                    'title' => 'Create New News',
                    'action' => 'create',
                    "values" => $values,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            App::redirect("/news");
        }
    }

    public function edit(int $id): void
    {
        $model = new NewsModel();
        $news = $model->get($id);
        if ($news === null) {
            $controller = new ErrorController();
            $controller->index(404, "News not found");
            return;
        }

        if (Request::method() === "GET") {
            $page = new NewsFormPage([
                'title' => 'Edit News',
                'action' => 'edit',
                "values" => $news,
            ]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $errors = [];

            $news['title'] =  Request::data("title");
            $news['description'] = Request::data('description');

            if (Request::file("image")) {
                $result = File::upload(
                    Request::file("image"),
                    "uploads" . DIRECTORY_SEPARATOR . "news",
                    ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                    ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                    5 * 1024 * 1024 // 5 MB in bytes
                );

                if ($result["success"]) {
                    $old_image_url = $news["image_url"];
                    $new_image_url = $result["path"];
                } else {
                    $errors["image"] = $result["message"];
                }
            }

            if (!empty($errors)) {
                if (isset($new_image_url)) {
                    File::delete($new_image_url);
                    unset($new_image_url);
                }

                $page = new NewsFormPage([
                    'title' => 'Edit News',
                    'action' => 'edit',
                    "values" => $news,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            if (isset($new_image_url)) {
                $news["image_url"] = $new_image_url;
            }

            $news = new NewsModel($news);
            $errors = $news->save();

            if (!empty($errors)) {
                if (isset($new_image_url)) {
                    File::delete($new_image_url);
                    unset($new_image_url);
                }

                $page = new NewsFormPage([
                    'title' => 'Edit News',
                    'action' => 'edit',
                    "values" => $news,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            if (isset($old_image_url)) {
                File::delete($old_image_url);
                unset($old_image_url);
            }

            App::redirect("/news/$id");
        }
    }

    public function delete(int $id): void
    {
        $model = new NewsModel();
        $news = $model->get($id);
        if ($news === null) {
            $controller = new ErrorController();
            $controller->index(404, "News not found");
            return;
        }

        $news = new NewsModel($news);
        $image_url = $news->data['image_url'] ?? null;

        if (!$news->delete()) {
            $controller = new ErrorController();
            $controller->index(500, "Cannot delete the News now, try again later!");
            return;
        }

        if ($image_url !== null) {
            File::delete($image_url);
        }

        App::redirect("/news");
    }
}

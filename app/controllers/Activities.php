<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Utils\Request;
use App\Utils\File;
use App\Models\Activity;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\ActivitiesList as ActivitiesListPage;
use App\Views\Pages\ActivityDetails as ActivityDetailsPage;
use App\Views\Pages\ActivityForm as ActivityFormPage;

class Activities extends Controller
{
    public function index(): void
    {
        $model = new Activity();
        $page = new ActivitiesListPage(['activities' => $model->all()]);
        $page->renderHtml();
    }

    public function details(int $id): void
    {
        $model = new Activity();
        $activity = $model->get($id);
        if ($activity === null) {
            $controller = new ErrorController();
            $controller->index(404, "Activity not found");
            return;
        }

        $page = new ActivityDetailsPage(["activity" => $activity]);
        $page->renderHtml();
    }

    public function create(): void
    {
        if (Request::method() === "GET") {
            $page = new ActivityFormPage([
                'title' => 'Create New Activity',
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
                    "uploads" . DIRECTORY_SEPARATOR . "activities",
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

                $page = new ActivityFormPage([
                    'title' => 'Create New Activity',
                    'action' => 'create',
                    "values" => $values,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            $activity = new Activity($values);
            $errors = $activity->save();

            if (!empty($errors)) {
                if (isset($values["image_url"])) {
                    File::delete($values["image_url"]);
                    unset($values["image_url"]);
                }

                $page = new ActivityFormPage([
                    'title' => 'Create New Activity',
                    'action' => 'create',
                    "values" => $values,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            App::redirect("/activities");
        }
    }

    public function edit(int $id): void
    {
        $model = new Activity();
        $activity = $model->get($id);
        if ($activity === null) {
            $controller = new ErrorController();
            $controller->index(404, "Activity not found");
            return;
        }

        if (Request::method() === "GET") {
            $page = new ActivityFormPage([
                'title' => 'Edit Activity',
                'action' => 'edit',
                "values" => $activity,
            ]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $errors = [];

            $activity['title'] =  Request::data("title");
            $activity['description'] = Request::data('description');

            if (Request::file("image")) {
                $result = File::upload(
                    Request::file("image"),
                    "uploads" . DIRECTORY_SEPARATOR . "activities",
                    ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                    ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                    5 * 1024 * 1024 // 5 MB in bytes
                );

                if ($result["success"]) {
                    $old_image_url = $activity["image_url"];
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

                $page = new ActivityFormPage([
                    'title' => 'Edit Activity',
                    'action' => 'edit',
                    "values" => $activity,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            if (isset($new_image_url)) {
                $activity["image_url"] = $new_image_url;
            }

            $activity = new Activity($activity);
            $errors = $activity->save();

            if (!empty($errors)) {
                if (isset($new_image_url)) {
                    File::delete($new_image_url);
                    unset($new_image_url);
                }

                $page = new ActivityFormPage([
                    'title' => 'Edit Activity',
                    'action' => 'edit',
                    "values" => $activity,
                    "errors" => $errors,
                ]);
                $page->renderHtml();
                return;
            }

            if (isset($old_image_url)) {
                File::delete($old_image_url);
                unset($old_image_url);
            }

            App::redirect("/activities/$id");
        }
    }

    public function delete(int $id): void
    {
        $model = new Activity();
        $activity = $model->get($id);
        if ($activity === null) {
            $controller = new ErrorController();
            $controller->index(404, "Activity not found");
            return;
        }

        $activity = new Activity($activity);
        $image_url = $activity->data['image_url'] ?? null;

        if (!$activity->delete()) {
            $controller = new ErrorController();
            $controller->index(500, "Cannot delete the activity now, try again later!");
            return;
        }

        if ($image_url !== null) {
            File::delete($image_url);
        }

        App::redirect("/activities");
    }
}

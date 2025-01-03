<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Utils\Request;
use App\Utils\File;
use App\Models\User;
use App\Models\Member;
use App\Controllers\Error as ErrorController;
use App\Views\Pages\MembersList as MembersListPage;
use App\Views\Pages\MemberDetails as MemberDetailsPage;
use App\Views\Pages\MemberEdit as MemberEditPage;

class Members extends Controller
{
    public function index(): void
    {
        $model = new Member();
        $page = new MembersListPage(["members" => $model->all()]);
        $page->renderHtml();
    }

    public function details(int $id): void
    {
        $user = User::current();
        if (($user["role"] === "member") && ($user["id"] !== $id)) {
            $controller = new ErrorController();
            $controller->index(403, "Forbidden");
            return;
        }

        $model = new Member();
        $member = $model->get($id);
        if ($member === null) {
            $controller = new ErrorController();
            $controller->index(404, "Member not found");
            return;
        }

        $page = new MemberDetailsPage(["member" => $member]);
        $page->renderHtml();
    }

    public function edit(int $id): void
    {
        $user = User::current();
        if (($user["role"] === "member") && ($user["id"] !== $id)) {
            $controller = new ErrorController();
            $controller->index(403, "Forbidden");
            return;
        }

        $model = new Member();
        $member = $model->get($id);
        if ($member === null) {
            $controller = new ErrorController();
            $controller->index(404, "Member not found");
            return;
        }

        if (Request::method() === "GET") {
            $page = new MemberEditPage(["member" => $member]);
            $page->renderHtml();
        } else if (Request::method() === "POST") {
            $errors = [];
            $values = [
                "old_password" => Request::data("old_password"),
                "new_password" => Request::data("new_password"),
                "confirm_password" => Request::data("confirm_password"),
            ];

            $member['is_active'] = Request::data('is_active') ? true : false;
            $member['first_name'] = Request::data('first_name');
            $member['last_name'] = Request::data('last_name');
            $member['email'] = Request::data('email');
            $member['phone'] = Request::data('phone');
            $member['birth_date'] = Request::data('birth_date');

            if (Request::file("member_image")) {
                $result = File::upload(
                    Request::file("member_image"),
                    "uploads" . DIRECTORY_SEPARATOR . "members",
                    ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                    ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                    5 * 1024 * 1024 // 5 MB in bytes
                );

                if ($result["success"]) {
                    $old_member_image_url = $member["member_image_url"];
                    $new_member_image_url = $result["path"];
                } else {
                    $errors["member_image"] = $result["message"];
                }
            }

            if (($values["new_password"] !== "") || ($values["confirm_password"] !== "")) {
                if (($user["role"] === "member") && ($member["password"] !== $values["old_password"])) {
                    $errors["old_password"] = "wrong password";
                }

                if ($values["new_password"] !== $values["confirm_password"]) {
                    $errors["confirm_password"] = "password mismatch";
                }

                $member["password"] = $values["new_password"];
            }

            if (!empty($errors)) {
                if (isset($new_member_image_url)) {
                    File::delete($new_member_image_url);
                    unset($new_member_image_url);
                }

                $page = new MemberEditPage(["member" => array_merge($member, $values), "errors" => $errors]);
                $page->renderHtml();
                return;
            }

            if (isset($new_member_image_url)) {
                $member["member_image_url"] = $new_member_image_url;
            }

            $member = new Member($member);
            $errors = $member->save();

            if (!empty($errors)) {
                if (isset($new_member_image_url)) {
                    File::delete($new_member_image_url);
                    unset($new_member_image_url);
                }

                $page = new MemberEditPage(["member" => array_merge($member->data, $values), "errors" => $errors]);
                $page->renderHtml();
                return;
            }

            if (isset($old_member_image_url)) {
                File::delete($old_member_image_url);
                unset($old_member_image_url);
            }

            App::redirect("/members/$id");
        }
    }

    public function delete(int $id): void
    {
        $user = User::current();
        if (($user["role"] === "member") && ($user["id"] !== $id)) {
            $controller = new ErrorController();
            $controller->index(403, "Forbidden");
            return;
        }

        $model = new Member();
        $member = $model->get($id);
        if ($member === null) {
            $controller = new ErrorController();
            $controller->index(404, "Member not found");
            return;
        }

        $member = new Member($member);

        if (!$member->delete()) {
            $controller = new ErrorController();
            $controller->index(500, "Cannot delete the member account, try again later!");
            return;
        }

        if ($user["role"] === "admin") {
            App::redirect("/members");
        } else {
            User::logout();
            App::redirect("/");
        }
    }
}

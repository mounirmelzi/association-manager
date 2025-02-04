<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Models\Member;
use App\Models\User;
use App\Utils\Request;
use App\Utils\Session;
use App\Utils\File;
use App\Views\Pages\Login as LoginPage;
use App\Views\Pages\Register as RegisterPage;

class Auth extends Controller
{
    public function login(): void
    {
        if (Request::method() === "GET") {
            $login_page = new LoginPage();
            $login_page->renderHtml();
        } else if (Request::method() === "POST") {
            $values = [
                "username" => Request::data("username"),
                "password" => Request::data("password")
            ];

            $errors = User::login($values["username"], $values["password"]);
            if (!empty($errors)) {
                $login_page = new LoginPage(["values" => $values, "errors" => $errors]);
                $login_page->renderHtml();
                return;
            }

            $session = new Session("auth");
            $role = $session->get("role");
            App::redirect(match ($role) {
                "admin" => "/dashboard",
                "member" => "/",
                "partner" => "/",
                default => "/",
            });
        }
    }

    public function register(): void
    {
        if (Request::method() === "GET") {
            $register_page = new RegisterPage();
            $register_page->renderHtml();
        } else if (Request::method() === "POST") {
            $errors = [];
            $values = [
                "first_name" => Request::data("first_name"),
                "last_name" => Request::data("last_name"),
                "username" => Request::data("username"),
                "email" => Request::data("email"),
                "phone" => Request::data("phone"),
                "birth_date" => Request::data("birth_date"),
                "password" => Request::data("password"),
                "confirm_password" => Request::data("confirm_password")
            ];

            $result = File::upload(
                Request::file("member_image"),
                "uploads" . DIRECTORY_SEPARATOR . "members",
                ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                5 * 1024 * 1024 // 5 MB in bytes
            );

            if ($result["success"]) {
                $values["member_image_url"] = $result["path"];
            } else {
                $errors["member_image"] = $result["message"];
            }

            $result = File::upload(
                Request::file("identity_image"),
                "uploads" . DIRECTORY_SEPARATOR . "members",
                ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
                5 * 1024 * 1024 // 5 MB in bytes
            );

            if ($result["success"]) {
                $values["identity_image_url"] = $result["path"];
            } else {
                $errors["identity_image"] = $result["message"];
            }

            if ($values["password"] !== $values["confirm_password"]) {
                $errors["confirm_password"] = "password mismatch";
            }

            if (!empty($errors)) {
                if (isset($values["member_image_url"])) {
                    File::delete($values["member_image_url"]);
                    unset($values["member_image_url"]);
                }

                if (isset($values["identity_image_url"])) {
                    File::delete($values["identity_image_url"]);
                    unset($values["identity_image_url"]);
                }

                $register_page = new RegisterPage(["values" => $values, "errors" => $errors]);
                $register_page->renderHtml();
                return;
            }

            $member = new Member($values);
            $errors = $member->save();

            if (!empty($errors)) {
                if (isset($values["member_image_url"])) {
                    File::delete($values["member_image_url"]);
                    unset($values["member_image_url"]);
                }

                if (isset($values["identity_image_url"])) {
                    File::delete($values["identity_image_url"]);
                    unset($values["identity_image_url"]);
                }

                $register_page = new RegisterPage(["values" => $values, "errors" => $errors]);
                $register_page->renderHtml();
                return;
            }

            App::redirect("/login");
        }
    }

    public function logout(): void
    {
        User::logout();
        App::redirect("/home");
    }
}

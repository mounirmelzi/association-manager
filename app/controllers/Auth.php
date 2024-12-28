<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Models\User;
use App\Utils\Request;
use App\Views\Pages\Login as LoginPage;

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
            if (empty($errors)) {
                App::redirect("/home");
            } else {
                $login_page = new LoginPage(["values" => $values, "errors" => $errors]);
                $login_page->renderHtml();
            }
        }
    }
}

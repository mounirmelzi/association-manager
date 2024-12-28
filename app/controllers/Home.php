<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Models\User;
use App\Views\Pages\Home as HomePage;

class Home extends Controller
{
    public function index(): void
    {
        $user = User::current();

        if ($user !== null) {
            $home_page = new HomePage(["message" => "Hello, world!"]);
            $home_page->renderHtml();
        } else {
            App::redirect("/login");
        }
    }
}

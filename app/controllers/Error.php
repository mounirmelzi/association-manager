<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Views\Pages\Error as ErrorPage;

class Error extends Controller
{
    public function index($error_code, $error_message): void
    {
        $error_page = new ErrorPage(compact("error_code", "error_message"));
        $error_page->renderHtml();
    }
}

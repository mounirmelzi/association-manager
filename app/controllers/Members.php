<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Member;
use App\Views\Pages\Members as MembersPage;

class Members extends Controller
{
    public function index(): void
    {
        $model = new Member();
        $members_page = new MembersPage(["members" => $model->all()]);
        $members_page->renderHtml();
    }
}

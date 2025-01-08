<?php

namespace App\Views\Pages;

use App\Views\Components\Table;
use App\Views\Components\Column;

class MembersList extends Page
{
    private Table $membersTable;

    public function __construct(array $data = []) {
        parent::__construct($data);

        $this->membersTable = new Table(
            title: 'Members List',
            data: $this->data["members"],
            config: [
                'minHeight' => "75vh",
            ],
            columns: [
                new Column(
                    label: 'Member',
                    renderer: function($member): void {
                        ?>
                            <div class="d-flex align-items-center">
                                <img 
                                    src="<?= BASE_URL . $member['member_image_url'] ?>" 
                                    alt="<?= $member['first_name'] ?>'s photo"
                                    class="rounded-circle me-3"
                                    width="40" 
                                    height="40"
                                    style="object-fit: cover"
                                >
                                <div>
                                    <div class="fw-medium"><?= $member['first_name'] ?> <?= $member['last_name'] ?></div>
                                    <div class="text-muted small">@<?= $member['username'] ?></div>
                                </div>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Contact Information',
                    renderer: function($member): void {
                        ?>
                            <div class="small">
                                <div><i class="bi bi-envelope text-muted me-2"></i><?= $member['email'] ?></div>
                                <div><i class="bi bi-telephone text-muted me-2"></i><?= $member['phone'] ?></div>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Birth Date',
                    renderer: function($member): void {
                        ?>
                            <div class="small">
                                <i class="bi bi-calendar text-muted me-2"></i>
                                <?= date('j F Y', strtotime($member['birth_date'])) ?>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Status',
                    renderer: function($member): void {
                        ?>
                            <span class="badge <?= $member['is_active'] ? 'text-bg-success' : 'text-bg-danger' ?>">
                                <?= $member['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        <?php
                    }
                ),
                new Column(
                    label: 'Joined Date',
                    renderer: function($member): void {
                        ?>
                            <div class="small text-muted">
                                <?= date('j F Y', strtotime($member['created_at'])) ?>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Actions',
                    width: '100px',
                    align: 'end',
                    renderer: function($member): void {
                        ?>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL . "members/$member[id]/edit" ?>">
                                            <i class="bi bi-pencil me-2"></i>
                                            Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL . "members/$member[id]" ?>">
                                            <i class="bi bi-briefcase me-2"></i>
                                            View Details
                                        </a>
                                    </li>
                                    <li> <hr class="dropdown-divider"></li>
                                    <li>
                                        <a
                                            class="dropdown-item text-danger"
                                            href="<?= BASE_URL . "members/$member[id]/delete" ?>"
                                            onclick="return confirm('Are you sure you want to delete this member?')"
                                        >
                                            <i class="bi bi-trash me-2"></i>
                                            Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php
                    }
                ),
            ],
        );
    }

    #[\Override]
    protected function head(): void
    {
        ?>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Members</title>

                <link href="<?= BASE_URL ?>css/libs/bootstrap.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>assets/icons/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
                
                <script src="<?= BASE_URL ?>js/libs/jquery-3.7.1.min.js" defer></script>
                <script src="<?= BASE_URL ?>js/libs/bootstrap.bundle.min.js" defer></script>
            </head>
        <?php
    }

    #[\Override]
    protected function body(): void
    {        
        ?>
            <body>
                <div class="container py-5">
                    <?= $this->membersTable->renderHtml() ?>
                </div>
            </body>
        <?php
    }
}
?>
<?php

namespace App\Views\Pages;

use App\Models\Navbar as NavbarModel;
use App\Views\Components\Navbar as NavbarComponent;
use App\Views\Components\Table;
use App\Views\Components\Column;

class VolunteeringsList extends Page
{
    private Table $volunteeringsTable;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $columns = [];

        $columns[] = new Column(
            label: 'User ID',
            renderer: function($volunteering): void {
                ?>
                    <a
                        href="<?= BASE_URL . "$volunteering[user_role]s/$volunteering[user_id]" ?>"
                        class="text-decoration-none"
                    >
                        <div class="d-flex align-items-center text-secondary">
                            <i class="bi bi-person-badge me-2"></i>
                            <span class="fw-medium"><?= $volunteering['user_role'] ?> #<?= $volunteering['user_id'] ?></span>
                        </div>
                    </a>
                <?php
            }
        );

        $columns[] = new Column(
            label: 'Email',
            renderer: function($volunteering): void {
                ?>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-envelope me-2 text-muted"></i>
                        <span><?= $volunteering['user_email'] ?></span>
                    </div>
                <?php
            }
        );

        $columns[] = new Column(
            label: 'Activity ID',
            renderer: function($volunteering): void {
                ?>
                    <a
                        href="<?= BASE_URL . "activities/$volunteering[activity_id]" ?>"
                        class="text-decoration-none"
                    >
                        <div class="d-flex align-items-center text-secondary">
                            <i class="bi bi-calendar2-event me-2"></i>
                            <span class="fw-medium">activity #<?= $volunteering['activity_id'] ?></span>
                        </div>
                    </a>
                <?php
            }
        );

        $columns[] = new Column(
            label: 'Title',
            renderer: function($volunteering): void {
                ?>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-bookmark me-2 text-muted"></i>
                        <span><?= $volunteering['activity_title'] ?></span>
                    </div>
                <?php
            }
        );

        $columns[] = new Column(
            label: 'Status',
            renderer: function($volunteering): void {
                $isValid = (bool)$volunteering['is_valid'];
                ?>
                    <div class="d-flex align-items-center">
                        <?php if ($isValid): ?>
                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                <i class="bi bi-check2-circle me-1"></i>
                                Valid
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                <i class="bi bi-slash-circle me-1"></i>
                                Invalid
                            </span>
                        <?php endif ?>
                    </div>
                <?php
            }
        );

        $columns[] = new Column(
            label: 'Actions',
            width: '100px',
            align: 'end',
            renderer: function($volunteering): void {
                $isValid = (bool)$volunteering['is_valid'];

                ?>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($isValid): ?>
                                <li>
                                    <a
                                        class="dropdown-item text-danger"
                                        href="<?= BASE_URL . "volunteerings/$volunteering[id]/validation" ?>"
                                        onclick="return confirm('Are you sure you want to invalidate this volunteering request?')"
                                    >
                                        <i class="bi bi-x-lg me-1"></i>
                                        Invalidate
                                    </a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a
                                        class="dropdown-item text-success"
                                        href="<?= BASE_URL . "volunteerings/$volunteering[id]/validation" ?>"
                                        onclick="return confirm('Are you sure you want to validate this volunteering request?')"
                                    >
                                        <i class="bi bi-check-lg me-1"></i>
                                        Validate
                                    </a>
                                </li>
                            <?php endif ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a
                                    class="dropdown-item text-danger"
                                    href="<?= BASE_URL . "volunteerings/$volunteering[id]/delete" ?>"
                                    onclick="return confirm('Are you sure you want to delete this volunteering request?')"
                                >
                                    <i class="bi bi-trash me-2"></i>
                                    Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php
            }
        );

        $this->volunteeringsTable = new Table(
            title: 'Volunteering Requests',
            data: $this->data["volunteerings"],
            columns: $columns,
            config: [
                'minHeight' => "70vh",
                'theme' => 'dark',
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
                <title>Volunteerings</title>

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
        $navbarModel = new NavbarModel();
        $navbarComponent = new NavbarComponent(['items' => $navbarModel->all()]);

        ?>
            <body>
                <section class="sticky-top">
                    <?php $navbarComponent->renderHtml() ?>
                </section>

                <div class="container py-5">
                    <?= $this->volunteeringsTable->renderHtml() ?>
                </div>
            </body>
        <?php
    }
}
?>
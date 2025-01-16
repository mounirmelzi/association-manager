<?php

namespace App\Views\Pages;

use App\Models\Navbar as NavbarModel;
use App\Views\Components\Navbar as NavbarComponent;
use App\Models\User;
use App\Views\Components\Table;
use App\Views\Components\Column;

class HelpsList extends Page
{
    private mixed $user;
    private Table $helpsTable;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->user = User::current();

        $columns = [];

        if ($this->user['role'] === 'admin')
        {
            $columns[] = new Column(
                label: 'User ID',
                renderer: function($help): void {
                    ?>
                        <a
                            href="<?= BASE_URL . "$help[user_role]s/$help[user_id]" ?>"
                            class="text-decoration-none"
                        >
                            <div class="d-flex align-items-center text-secondary">
                                <i class="bi bi-person-badge me-2"></i>
                                <span class="fw-medium"><?= $help['user_role'] ?> #<?= $help['user_id'] ?></span>
                            </div>
                        </a>
                    <?php
                }
            );
    
            $columns[] = new Column(
                label: 'Email',
                renderer: function($help): void {
                    ?>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope me-2 text-muted"></i>
                            <span><?= $help['user_email'] ?></span>
                        </div>
                    <?php
                }
            );
        }

        $columns[] = new Column(
            label: 'Description',
            renderer: function($help): void {
                ?>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-file-text me-2 text-muted"></i>
                        <span><?= mb_strimwidth($help['description'], 0, 50, "...") ?></span>
                    </div>
                <?php
            }
        );

        $columns[] = new Column(
            label: 'Attachments',
            renderer: function($help): void {
                if (!empty($help['attachments_url'])) {
                    ?>
                        <div class="d-flex align-items-center">
                            <a
                                href="<?= BASE_URL . $help['attachments_url'] ?>"
                                download
                                class="btn btn-sm btn-outline-secondary"
                            >
                                <i class="bi bi-file-earmark-zip me-1"></i>
                                Download ZIP
                            </a>
                        </div>
                    <?php
                } else {
                    ?>
                        <span class="text-muted">No attachments</span>
                    <?php
                }
            }
        );

        $columns[] = new Column(
            label: 'Help Type',
            renderer: function($help): void {
                ?>
                    <span class="badge bg-secondary text-white">
                        <?= ucwords(str_replace('_', ' ', $help['help_type'])) ?>
                    </span>
                <?php
            }
        );

        $columns[] = new Column(
            label: 'Date',
            renderer: function($help): void {
                ?>
                    <div class="small">
                        <i class="bi bi-calendar text-muted me-2"></i>
                        <?= date('j F Y', strtotime($help['date'])) ?>
                        <div class="text-muted">
                            <?= date('H:i', strtotime($help['date'])) ?>
                        </div>
                    </div>
                <?php
            }
        );

        $columns[] = new Column(
            label: 'Status',
            renderer: function($help): void {
                $isValid = (bool)$help['is_valid'];
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

        if ($this->user['role'] === 'admin')
        {
            $columns[] = new Column(
                label: 'Actions',
                width: '100px',
                align: 'end',
                renderer: function($help): void {
                    $isValid = (bool)$help['is_valid'];
    
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
                                            href="<?= BASE_URL . "helps/$help[id]/validation" ?>"
                                            onclick="return confirm('Are you sure you want to invalidate this help request?')"
                                        >
                                            <i class="bi bi-x-lg me-1"></i>
                                            Invalidate
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <a
                                            class="dropdown-item text-success"
                                            href="<?= BASE_URL . "helps/$help[id]/validation" ?>"
                                            onclick="return confirm('Are you sure you want to validate this help request?')"
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
                                        href="<?= BASE_URL . "helps/$help[id]/delete" ?>"
                                        onclick="return confirm('Are you sure you want to delete this help request?')"
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
        }

        $config = [
            'minHeight' => "70vh",
            'createText' => 'Submit new help request',
            'createUrl' => BASE_URL . "helps/create",
            'theme' => 'dark',
        ];

        if ($this->user['role'] === 'admin')
        {
            unset($config['createText']);
            unset($config['createUrl']);
        }

        $this->helpsTable = new Table(
            title: 'Help Requests',
            data: $this->data["helps"],
            columns: $columns,
            config: $config,
        );
    }

    #[\Override]
    protected function head(): void
    {
        ?>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Help Requests</title>

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

                <div class="container my-5">
                    <?= $this->helpsTable->renderHtml() ?>
                </div>
            </body>
        <?php
    }
}
?>
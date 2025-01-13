<?php

namespace App\Views\Pages;

use App\Models\Navbar as NavbarModel;
use App\Views\Components\Navbar as NavbarComponent;
use App\Views\Components\Table;
use App\Views\Components\Column;

class NewsList extends Page
{
    private Table $newsTable;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->newsTable = new Table(
            title: 'News List',
            data: $this->data["news"],
            config: [
                'minHeight' => "70vh",
                'createText' => 'Create New News',
                'createUrl' => BASE_URL . "news/create",
                'theme' => 'dark',
            ],
            columns: [
                new Column(
                    label: 'News',
                    renderer: function($news): void {
                        ?>
                            <div class="d-flex align-items-center">
                                <img
                                    src="<?= BASE_URL . $news['image_url'] ?>"
                                    alt="<?= $news['title'] ?> image"
                                    class="rounded me-3"
                                    width="60"
                                    height="40"
                                    style="object-fit: cover"
                                >
                                <div>
                                    <div class="fw-medium">
                                        <?= htmlspecialchars($news['title']) ?>
                                    </div>
                                    <div class="text-muted small">
                                        <?=
                                            substr(
                                                htmlspecialchars($news['description']),
                                                0,
                                                100
                                            ) . '...'
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Created At',
                    renderer: function($news): void {
                        ?>
                            <div class="small">
                                <i class="bi bi-calendar text-muted me-2"></i>
                                <?= date('j F Y', strtotime($news['date'])) ?>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Actions',
                    width: '100px',
                    align: 'end',
                    renderer: function($news): void {
                        ?>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL . "news/$news[id]/edit" ?>">
                                            <i class="bi bi-pencil me-2"></i>
                                            Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL . "news/$news[id]" ?>">
                                            <i class="bi bi-eye me-2"></i>
                                            View Details
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a
                                            class="dropdown-item text-danger"
                                            href="<?= BASE_URL . "news/$news[id]/delete" ?>"
                                            onclick="return confirm('Are you sure you want to delete this news?')"
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
                <title>News</title>

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
                    <?= $this->newsTable->renderHtml() ?>
                </div>
            </body>
        <?php
    }
}

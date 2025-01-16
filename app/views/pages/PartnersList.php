<?php

namespace App\Views\Pages;

use App\Models\Navbar as NavbarModel;
use App\Views\Components\Navbar as NavbarComponent;
use App\Views\Components\Table;
use App\Views\Components\Column;

class PartnersList extends Page
{
    private Table $partnersTable;

    public function __construct(array $data = []) {
        parent::__construct($data);

        $this->partnersTable = new Table(
            title: 'Partners List',
            data: $this->data["partners"],
            config: [
                'minHeight' => "70vh",
                'createText' => 'Create Partner',
                'createUrl' => BASE_URL . "partners/create",
                'theme' => 'dark',
            ],
            columns: [
                new Column(
                    label: 'Partner',
                    renderer: function($partner): void {
                        ?>
                            <div class="d-flex align-items-center">
                                <img
                                    src="<?= BASE_URL . $partner['logo_url'] ?>"
                                    alt="<?= $partner['name'] ?>'s photo"
                                    class="rounded-circle me-3"
                                    width="40"
                                    height="40"
                                    style="object-fit: cover"
                                >
                                <div>
                                    <div class="fw-medium">
                                        <?= htmlspecialchars($partner['name']) ?>
                                    </div>
                                    <div class="text-muted small">
                                        <?= htmlspecialchars($partner['category']) ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Contact Information',
                    renderer: function($partner): void {
                        ?>
                            <div class="small">
                                <div>
                                    <i class="bi bi-envelope text-muted me-2"></i>
                                    <?= htmlspecialchars($partner['email']) ?>
                                </div>
                                <div>
                                    <i class="bi bi-telephone text-muted me-2"></i>
                                    <?= htmlspecialchars($partner['phone']) ?>
                                </div>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Address',
                    renderer: function($partner): void {
                        ?>
                            <div class="small">
                                <i class="bi bi-house-door text-muted me-2"></i>
                                <?= htmlspecialchars($partner['address']) ?>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Joined Date',
                    renderer: function($partner): void {
                        ?>
                            <div class="small text-muted">
                                <?= date('j F Y', strtotime($partner['created_at'])) ?>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Actions',
                    width: '100px',
                    align: 'end',
                    renderer: function($partner): void {
                        ?>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL . "partners/$partner[id]/edit" ?>">
                                            <i class="bi bi-pencil me-2"></i>
                                            Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL . "partners/$partner[id]" ?>">
                                            <i class="bi bi-briefcase me-2"></i>
                                            View Details
                                        </a>
                                    </li>
                                    <li> <hr class="dropdown-divider"></li>
                                    <li>
                                        <a
                                            class="dropdown-item text-danger"
                                            href="<?= BASE_URL . "partners/$partner[id]/delete" ?>"
                                            onclick="return confirm('Are you sure you want to delete this partner?')"
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
                <title>Partners</title>

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

                <main class="container py-5">
                    <?= $this->partnersTable->renderHtml() ?>
                </main>
            </body>
        <?php
    }
}
?>
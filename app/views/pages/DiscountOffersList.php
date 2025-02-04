<?php

namespace App\Views\Pages;

use App\Models\Navbar as NavbarModel;
use App\Views\Components\Navbar as NavbarComponent;
use App\Views\Components\Table;
use App\Views\Components\Column;

class DiscountOffersList extends Page {
    private Table $discountsTable;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->discountsTable = new Table(
            title: 'Discount Offers List',
            data: $this->data["discounts"],
            config: [
                'minHeight' => "70vh",
                'createText' => 'Create New Discount Offer',
                'createUrl' => BASE_URL . "discounts/offers/create",
                'theme' => 'dark',
            ],
            columns: [
                new Column(label: 'Partner Name', key: 'partner_name'),
                new Column(label: 'Partner Category', key: 'partner_category'),
                new Column(label: 'Partner Address', key: 'partner_address'),
                new Column(label: 'Card Type', key: 'card_type'),
                new Column(label: 'Percentage', key: 'percentage'),
                new Column(
                    label: 'Actions',
                    width: '100px',
                    align: 'end',
                    renderer: function($discount): void {
                        ?>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL . "discounts/offers/$discount[id]/edit" ?>">
                                            <i class="bi bi-pencil me-2"></i>
                                            Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a
                                            class="dropdown-item text-danger"
                                            href="<?= BASE_URL . "discounts/offers/$discount[id]/delete" ?>"
                                            onclick="return confirm('Are you sure you want to delete this discount offer?')"
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
            ]
        );
    }

    #[\Override]
    protected function head(): void
    {
        ?>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Discount Offers</title>

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
                    <?= $this->discountsTable->renderHtml() ?>
                </main>
            </body>
        <?php
    }
}

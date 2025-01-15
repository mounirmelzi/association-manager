<?php

namespace App\Views\Pages;

use App\Models\Navbar as NavbarModel;
use App\Views\Components\Navbar as NavbarComponent;
use App\Views\Components\Table;
use App\Views\Components\Column;
use DateTime;

class Discounts extends Page
{
    #[\Override]
    protected function head(): void
    {
        ?>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Discounts</title>

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

                <main class="container my-5">
                    <?php $this->renderDiscounts() ?>
                </main>

                <section class="container my-5">
                    <?php $this->renderLimitedDiscounts() ?>
                </section>
            </body>
        <?php
    }

    private function renderDiscounts(): void
    {
        $data = $this->data['discounts'];

        $columns = [
            new Column(label: 'Partner', key: 'partner_name'),
            new Column(label: 'Category', key: 'partner_category'),
            new Column(label: 'Address', key: 'partner_address'),
            new Column(label: 'Card Type', key: 'card_type'),
            new Column(label: 'Discount Percentage', key: 'percentage'),
        ];

        $table = new Table(
            title: 'Discounts',
            data: $data,
            columns: $columns,
        );

        $table->renderHtml();
    }

    private function renderLimitedDiscounts(): void
    {
        $data = $this->data['limitedDiscounts'];

        $columns = [
            new Column(label: 'Partner', key: 'partner_name'),
            new Column(label: 'Category', key: 'partner_category'),
            new Column(label: 'Address', key: 'partner_address'),
            new Column(label: 'Card Type', key: 'card_type'),
            new Column(label: 'Discount Percentage', key: 'percentage'),
            new Column(label: 'Start Date', key: 'start_date', renderer: function($data) {
                return (new DateTime($data['start_date']))->format('Y-m-d H:i:s');
            }),
            new Column(label: 'End Date', key: 'end_date', renderer: function($data) {
                return (new DateTime($data['end_date']))->format('Y-m-d H:i:s');
            }),
        ];

        $table = new Table(
            title: 'Limited Discounts',
            data: $data,
            columns: $columns,
        );

        $table->renderHtml();
    }
}
?>
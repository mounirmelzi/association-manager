<?php

namespace App\Views\Pages;

use App\Models\Navbar as NavbarModel;
use App\Views\Components\Navbar as NavbarComponent;

class Statistics extends Page
{
    #[\Override]
    protected function head(): void {
        ?>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Statistics</title>

                <link href="<?= BASE_URL ?>css/libs/bootstrap.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>assets/icons/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>css/pages/statistics.css" rel="stylesheet">

                <script src="<?= BASE_URL ?>js/libs/jquery-3.7.1.min.js" defer></script>
                <script src="<?= BASE_URL ?>js/libs/bootstrap.bundle.min.js" defer></script>
            </head>
        <?php
    }

    #[\Override]
    protected function body(): void {
        $navbarModel = new NavbarModel();
        $navbarComponent = new NavbarComponent(['items' => $navbarModel->all()]);

        ?>
            <body>
                <section class="sticky-top">
                    <?php $navbarComponent->renderHtml() ?>
                </section>

                <main class="container my-5">
                    <h1 class="text-center mb-4">Statistics</h1>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-header text-white bg-primary">
                                    General Statistics
                                </div>
                                <div class="card-body">
                                    <p><strong>Total Admins:</strong> <?= $this->data['total_admins'] ?></p>
                                    <p><strong>Total Partners:</strong> <?= $this->data['total_partners'] ?></p>
                                    <p><strong>Active Members:</strong> <?= $this->data['active_members'] ?></p>
                                    <p><strong>Total Helps:</strong> <?= $this->data['total_helps'] ?></p>
                                    <p><strong>Total Volunteerings:</strong> <?= $this->data['total_volunteerings'] ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-header text-white bg-success">
                                    Cards Distribution
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <?php foreach ($this->data['cards_distribution'] as $card): ?>
                                            <li class="list-group-item">
                                                <strong><?= $card['type'] ?>:</strong> <?= $card['total_cards'] ?> cards
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-header text-white bg-warning">
                                    Partners Distribution
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <?php foreach ($this->data['partners_distribution'] as $partner): ?>
                                            <li class="list-group-item">
                                                <strong><?= $partner['category'] ?>:</strong> <?= $partner['total_partners'] ?> partners
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-header text-white bg-info">
                                    Payments Statistics
                                </div>
                                <div class="card-body">
                                    <p><strong>Total Payments:</strong> <?= $this->data['payments']['total_payments'] ?></p>
                                    <p><strong>Total Amount:</strong> <?= $this->data['payments']['total_amount'] ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-header text-white bg-danger">
                                    Discounts by Partner
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Partner Name</th>
                                                <th>Category</th>
                                                <th>Total Discount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($this->data['total_discount'] as $discount): ?>
                                                <tr>
                                                    <td><?= $discount['partner_name'] ?></td>
                                                    <td><?= $discount['partner_category'] ?></td>
                                                    <td><?= $discount['total_discount'] ?></td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </body>
        <?php
    }
}

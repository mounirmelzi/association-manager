<?php

namespace App\Views\Pages;

use App\Views\Components\Table;
use App\Views\Components\Column;

class PartnerDetails extends Page {
    private Table $discountsTable;
    private Table $limitedDiscountsTable;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->discountsTable = new Table(
            title: 'Discounts',
            data: $this->data["discounts"],
            columns: [
                new Column('Card Type', 'card_type'),
                new Column('Percentage', 'percentage'),
            ],
        );

        $this->limitedDiscountsTable = new Table(
            title: 'Limited Discounts',
            data: $this->data["limitedDiscounts"],
            columns: [
                new Column('Card Type', 'card_type'),
                new Column('Percentage', 'percentage'),
                new Column('Start', 'start_date'),
                new Column('End', 'end_date'),
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
                <title>Partner Details</title>

                <link href="<?= BASE_URL ?>css/libs/bootstrap.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>assets/icons/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">

                <script src="<?= BASE_URL ?>js/libs/jquery-3.7.1.min.js" defer></script>
                <script src="<?= BASE_URL ?>js/libs/bootstrap.min.js" defer></script>
            </head>
        <?php
    }

    #[\Override]
    protected function body(): void
    {
        $partner = $this->data["partner"];

        ?>
            <body>
                <div class="container py-5">
                    <div class="card shadow-lg">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Partner Details</h3>
                            <div class="d-flex gap-2">
                                <a
                                    href="<?= BASE_URL . "partners/$partner[id]/edit" ?>"
                                    class="btn btn-primary btn-sm px-3"
                                >
                                    <i class="bi bi-pencil me-2"></i>
                                    Edit
                                </a>
                                <a
                                    class="btn btn-danger btn-sm px-3"
                                    href="<?= BASE_URL . "partners/$partner[id]/delete" ?>"
                                    onclick="return confirm('Are you sure you want to delete this partner?')"
                                >
                                    <i class="bi bi-trash me-2"></i>
                                    Delete
                                </a>
                            </div>
                        </div>

                        <div class="card-body mt-4">
                            <div class="row">
                                <div class="col-md-4 text-center mb-4">
                                    <img
                                        src="<?= BASE_URL . htmlspecialchars($partner['logo_url']) ?>"
                                        class="img-fluid rounded-circle mb-4"
                                        alt="Profile Picture"
                                        style="width: 200px; height: 200px; object-fit: cover;"
                                    >
                                    <h4>
                                        <?= htmlspecialchars($partner['name']) ?>
                                    </h4>
                                    <p class="text-muted">
                                        <?= htmlspecialchars($partner['description']) ?>
                                    </p>
                                </div>

                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="fw-bold">Category</label>
                                            <p>
                                                <?= htmlspecialchars($partner['category']) ?>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Username</label>
                                            <p>
                                                <?= htmlspecialchars($partner['username']) ?>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Email</label>
                                            <p>
                                                <?= htmlspecialchars($partner['email']) ?>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Phone</label>
                                            <p>
                                                <?= htmlspecialchars($partner['phone']) ?>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Address</label>
                                            <p>
                                                <?= htmlspecialchars($partner['address']) ?>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Partner Since</label>
                                            <p>
                                                <?= htmlspecialchars($partner['created_at']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container py-5">
                    <?= $this->discountsTable->renderHtml() ?>
                </div>

                <div class="container py-5">
                    <?= $this->limitedDiscountsTable->renderHtml() ?>
                </div>
            </body>
        <?php
    }
}

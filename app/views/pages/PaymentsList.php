<?php

namespace App\Views\Pages;

use App\Models\Navbar as NavbarModel;
use App\Views\Components\Navbar as NavbarComponent;
use App\Models\User;
use App\Views\Components\Table;
use App\Views\Components\Column;

class PaymentsList extends Page
{
    private mixed $user;
    private Table $paymentsTable;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->user = User::current();

        $columns = [];

        if ($this->user['role'] === 'admin')
        {
            $columns[] = new Column(
                label: 'User ID',
                renderer: function($payment): void {
                    ?>
                        <a
                            href="<?= BASE_URL . "$payment[user_role]s/$payment[user_id]" ?>"
                            class="text-decoration-none"
                        >
                            <div class="d-flex align-items-center text-secondary">
                                <i class="bi bi-person-badge me-2"></i>
                                <span class="fw-medium"><?= $payment['user_role'] ?> #<?= $payment['user_id'] ?></span>
                            </div>
                        </a>
                    <?php
                }
            );
    
            $columns[] = new Column(
                label: 'Email',
                renderer: function($payment): void {
                    ?>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope me-2 text-muted"></i>
                            <span><?= $payment['user_email'] ?></span>
                        </div>
                    <?php
                }
            );
        }

        $columns[] = new Column(
            label: 'Amount',
            renderer: function($payment): void {
                ?>
                    <div class="d-flex align-items-center">
                        <span class="fw-bold">
                            <?= number_format((float)$payment['amount'], 2) ?>
                        </span>
                    </div>
                <?php
            }
        );

        $columns[] = new Column(
            label: 'Receipt',
            renderer: function($payment): void {
                if (!empty($payment['receipt_image_url'])) {
                    ?>
                        <div class="d-flex align-items-center">
                            <a
                                href="<?= BASE_URL . $payment['receipt_image_url'] ?>"
                                target="_blank"
                                class="btn btn-sm btn-outline-secondary"
                            >
                                <i class="bi bi-image me-1"></i>
                                View Receipt
                            </a>
                        </div>
                    <?php
                } else {
                    ?>
                        <span class="text-muted">No receipt</span>
                    <?php
                }
            }
        );

        $columns[] = new Column(
            label: 'Type',
            renderer: function($payment): void {
                ?>
                    <span class="badge bg-secondary text-white">
                        <?= ucwords(str_replace('_', ' ', $payment['type'])) ?>
                    </span>
                <?php
            }
        );

        $columns[] = new Column(
            label: 'Date',
            renderer: function($payment): void {
                ?>
                    <div class="small">
                        <i class="bi bi-calendar text-muted me-2"></i>
                        <?= date('j F Y', strtotime($payment['date'])) ?>
                        <div class="text-muted">
                            <?= date('H:i', strtotime($payment['date'])) ?>
                        </div>
                    </div>
                <?php
            }
        );

        $columns[] = new Column(
            label: 'Status',
            renderer: function($payment): void {
                $isValid = (bool)$payment['is_valid'];
                ?>
                    <div class="d-flex align-items-center">
                        <?php if ($isValid): ?>
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Valid
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle-fill me-1"></i>
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
                renderer: function($payment): void {
                    $isValid = (bool)$payment['is_valid'];
    
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
                                            href="<?= BASE_URL . "payments/$payment[id]/validation" ?>"
                                            onclick="return confirm('Are you sure you want to invalidate this payment?')"
                                        >
                                            <i class="bi bi-x-lg me-1"></i>
                                            Invalidate
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <a
                                            class="dropdown-item text-success"
                                            href="<?= BASE_URL . "payments/$payment[id]/validation" ?>"
                                            onclick="return confirm('Are you sure you want to validate this payment?')"
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
                                        href="<?= BASE_URL . "payments/$payment[id]/delete" ?>"
                                        onclick="return confirm('Are you sure you want to delete this payment?')"
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
            'createText' => 'Make a new payment',
            'createUrl' => BASE_URL . "payments/create",
            'theme' => 'dark',
        ];

        if ($this->user['role'] === 'admin')
        {
            unset($config['createText']);
            unset($config['createUrl']);
        }

        $this->paymentsTable = new Table(
            title: 'Payments Gate',
            data: $this->data["payments"],
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
                <title>Payments Gate</title>

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
                    <?= $this->paymentsTable->renderHtml() ?>
                </div>
            </body>
        <?php
    }
}
?>
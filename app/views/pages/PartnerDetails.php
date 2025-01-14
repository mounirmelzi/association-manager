<?php

namespace App\Views\Pages;

use App\Models\User;
use App\Views\Components\Table;
use App\Views\Components\Column;
use App\Views\Components\Input;
use App\Models\CardType;

class PartnerDetails extends Page {
    private Table $discountsTable;
    private Table $limitedDiscountsTable;
    private Input $cardTypeInput;

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

        $cardTypeModel = new CardType();
        $this->cardTypeInput = new Input(
            name: 'card_type_id',
            value: $this->data["values"]["card_type_id"] ?? null,
            error: $this->data["errors"]["card_type_id"] ?? null,
            config: [
                'type' => 'select',
                'options' =>
                    array_map(function ($type) {
                        return [
                            'name' => "[$type[id]] - $type[type] ($type[fee])",
                            'value' => $type['id'],
                        ];
                    }, $cardTypeModel->all()),
                'icon' => 'person-vcard',
                'placeholder' => 'Card Type',
                'label' => 'Card Type',
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
                <title>Partner Details</title>

                <link href="<?= BASE_URL ?>css/libs/bootstrap.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>assets/icons/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>css/pages/partner-details.css" rel="stylesheet">

                <script src="<?= BASE_URL ?>js/libs/jquery-3.7.1.min.js" defer></script>
                <script src="<?= BASE_URL ?>js/libs/bootstrap.bundle.min.js" defer></script>
            </head>
        <?php
    }

    #[\Override]
    protected function body(): void
    {
        $partner = $this->data["partner"];
        $user = User::current();
        $haveFullAccess =
            ($user !== null)
            &&
            (
                ($user['role'] === 'admin')
                ||
                (
                    ($user["role"] === "partner")
                    &&
                    ($user["id"] === $partner['id'])
                )
            )
        ;

        ?>
            <body>
                <div class="container my-5">
                    <div class="card shadow-lg">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Partner Details</h3>
                            <?php if ($haveFullAccess): ?>
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
                            <?php endif ?>
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
                                        <?php if ($haveFullAccess): ?>
                                            <div class="col-md-6">
                                                <label class="fw-bold">Username</label>
                                                <p>
                                                    <?= htmlspecialchars($partner['username']) ?>
                                                </p>
                                            </div>
                                        <?php endif ?>
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

                <?php if ($haveFullAccess): ?>
                    <div class="container my-5">
                        <?php $this->renderCards() ?>
                        <?php $this->renderCreateModal() ?>
                    </div>
                <?php endif ?>

                <div class="container my-5">
                    <?= $this->discountsTable->renderHtml() ?>
                </div>

                <div class="container my-5">
                    <?= $this->limitedDiscountsTable->renderHtml() ?>
                </div>
            </body>
        <?php
    }

    private function renderCards(): void
    {
        $user = User::current();

        ?>
            <div class="subscription-card-container">
                <?php if (empty($this->data['cards'])): ?>
                    <div class="subscription-card-container">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <p class="empty-state-text">No subscription cards found</p>
                            <?php if ($user['role'] === 'admin'): ?>
                                <button
                                    type="button"
                                    class="add-card-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#create-modal"
                                >
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            <?php endif ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="subscription-wrapper">
                        <div class="subscription-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Subscription Cards</h4>
                            <div class="card-nav-buttons d-flex">
                                <?php if (count($this->data['cards']) > 1): ?>
                                    <button
                                        class="card-nav-btn"
                                        type="button"
                                        data-bs-target="#subscriptionCarousel"
                                        data-bs-slide="prev"
                                    >
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    <button
                                        class="card-nav-btn"
                                        type="button"
                                        data-bs-target="#subscriptionCarousel"
                                        data-bs-slide="next"
                                    >
                                        <i class="bi bi-chevron-right"></i>
                                    </button>
                                <?php endif ?>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <button
                                        type="button"
                                        class="add-card-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#create-modal"
                                    >
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="subscription-carousel">
                            <div id="subscriptionCarousel" class="carousel slide" data-bs-touch="false">
                                <div class="carousel-inner">
                                    <?php foreach ($this->data['cards'] as $index => $card): ?>
                                        <?php
                                            $username = $this->data['partner']['username'];
                                            $name = $this->data['partner']['name'];
                                            $role = 'partner';
                                            $cardType = strtolower($card['type']['type']);
                                            $cardFee = $card['type']['fee'];
                                            $cardExpirationDate = $card['expiration_date'];
                                            $cardQrcodeImageUrl = $card['qrcode_image_url'];
                                            $isExpired = strtotime($cardExpirationDate) < time();
                                        ?>
                                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                            <div class="d-flex justify-content-center">
                                                <div class="partnership-card">
                                                    <div class="card-content">
                                                        <div class="card-header-content d-flex justify-content-between">
                                                            <div>
                                                                <h5 class="card-title"><?= ucfirst($cardType) ?> Card</h5>
                                                                <p class="partner-name">
                                                                    <?= $name ?>
                                                                </p>
                                                                <span class="role-badge"><?= ucfirst($role) ?></span>
                                                            </div>
                                                            <div class="fee-display">
                                                                <div class="fee-amount"><?= number_format($cardFee, 2) ?> €</div>
                                                                <div class="fee-label">Annual Fee</div>
                                                            </div>
                                                        </div>
                                                        <div class="card-details">
                                                            <div class="details-left">
                                                                <div class="detail-item">
                                                                    <div class="detail-label">Partnership ID</div>
                                                                    <div class="detail-value"><?= $username ?></div>
                                                                </div>
                                                                <div class="detail-item">
                                                                    <div class="detail-label">Valid Until</div>
                                                                    <div class="detail-value">
                                                                        <?= date('M d, Y', strtotime($cardExpirationDate)) ?>
                                                                        <?php if ($isExpired): ?>
                                                                            <span class="expired-badge">Expired</span>
                                                                        <?php endif ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php if ($cardQrcodeImageUrl): ?>
                                                                <div class="qr-code-container">
                                                                    <img
                                                                        src="<?= BASE_URL . $cardQrcodeImageUrl ?>" 
                                                                        alt="QR Code" 
                                                                        class="qr-code"
                                                                    >
                                                                </div>
                                                            <?php endif ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const carousel = new bootstrap.Carousel(document.getElementById('subscriptionCarousel'), {
                        interval: false,
                        wrap: true,
                        touch: false
                    });
                });
            </script>
        <?php
    }

    private function renderCreateModal(): void {
        $partnerId = $this->data["partner"]['id'];
        $action = "partners/$partnerId/cards/create";

        ?>
            <div class="modal fade" id="create-modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Create Card</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form
                            action="<?= BASE_URL . $action ?>"
                            method="POST"
                            enctype="multipart/form-data"
                        >
                            <div class="modal-body">
                                <div class="mb-3">
                                    <?= $this->cardTypeInput->renderHtml() ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
    }
}
?>
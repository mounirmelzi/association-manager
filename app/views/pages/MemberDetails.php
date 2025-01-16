<?php

namespace App\Views\Pages;

use App\Models\Navbar as NavbarModel;
use App\Views\Components\Navbar as NavbarComponent;
use App\Models\User;
use App\Views\Components\Table;
use App\Views\Components\Column;
use App\Views\Components\Input;
use App\Models\CardType;

class MemberDetails extends Page
{
    private Table $discountsTable;
    private Input $cardTypeInput;

    public function __construct(array $data = []) {
        parent::__construct($data);

        $user = User::current();

        $this->discountsTable = new Table(
            title: 'Discounts Tracker',
            data: $this->data["discounts"],
            columns: array_merge([
                new Column(
                    label: 'Partner',
                    renderer: function($discount): void {
                        ?>
                            <div class="d-flex align-items-center">
                                <img
                                    src="<?= BASE_URL . $discount['partner']['logo_url'] ?>"
                                    alt="<?= $discount['partner']['name'] ?>'s photo"
                                    class="rounded-circle me-3"
                                    width="40"
                                    height="40"
                                    style="object-fit: cover"
                                >
                                <div>
                                    <div class="fw-medium">
                                        <?= htmlspecialchars($discount['partner']['name']) ?>
                                    </div>
                                    <div class="text-muted small">
                                        <?= htmlspecialchars($discount['partner']['category']) ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Address',
                    renderer: function($discount): void {
                        ?>
                            <div class="small">
                                <i class="bi bi-house-door text-muted me-2"></i>
                                <?= htmlspecialchars($discount['partner']['address']) ?>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Amount',
                    renderer: function($discount): void {
                        ?>
                            <div class="fw-medium">
                                <?= number_format($discount['amount'], 2, '.', ',') ?>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Description',
                    renderer: function($discount): void {
                        ?>
                            <div class="small text-wrap" style="max-width: 250px;">
                                <?= htmlspecialchars($discount['description']) ?>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Date',
                    renderer: function($discount): void {
                        ?>
                            <div class="small">
                                <i class="bi bi-calendar text-muted me-2"></i>
                                <?= date('j F Y', strtotime($discount['date'])) ?>
                                <div class="text-muted">
                                    <?= date('H:i', strtotime($discount['date'])) ?>
                                </div>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Status',
                    renderer: function($discount): void {
                        $isValid = (bool)$discount['is_valid'];
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
                ),
            ], ($user['role'] === 'member') ? [
                new Column(
                    label: 'Actions',
                    width: '100px',
                    align: 'end',
                    renderer: function($discount): void {
                        $isValid = (bool)$discount['is_valid'];
                        $member = $this->data['member'];
                        $redirectUrl = "/members/$member[id]";
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
                                                href="<?= BASE_URL . "discounts/$discount[id]/validation?redirect=$redirectUrl" ?>"
                                                onclick="return confirm('Are you sure you want to invalidate this discount?')"
                                            >
                                                <i class="bi bi-x-lg me-1"></i>
                                                Invalidate
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li>
                                            <a
                                                class="dropdown-item text-success"
                                                href="<?= BASE_URL . "discounts/$discount[id]/validation?redirect=$redirectUrl" ?>"
                                                onclick="return confirm('Are you sure you want to validate this discount?')"
                                            >
                                                <i class="bi bi-check-lg me-1"></i>
                                                Validate
                                            </a>
                                        </li>
                                    <?php endif ?>
                                </ul>
                            </div>
                        <?php
                    }
                ),
            ] : []),
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
                <title>Member Details</title>

                <link href="<?= BASE_URL ?>css/libs/bootstrap.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>assets/icons/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>css/pages/member-details.css" rel="stylesheet">

                <script src="<?= BASE_URL ?>js/libs/jquery-3.7.1.min.js" defer></script>
                <script src="<?= BASE_URL ?>js/libs/bootstrap.bundle.min.js" defer></script>
            </head>
        <?php
    }

    #[\Override]
    protected function body(): void
    {
        $user = User::current();
        $member = $this->data["member"];
        $haveFullAccess =
            ($user !== null)
            &&
            (
                ($user['role'] === 'admin')
                ||
                (
                    ($user["role"] === "member")
                    &&
                    ($user["id"] === $member['id'])
                )
            )
        ;

        $navbarModel = new NavbarModel();
        $navbarComponent = new NavbarComponent(['items' => $navbarModel->all()]);

        ?>
            <body>
                <section class="sticky-top">
                    <?php $navbarComponent->renderHtml() ?>
                </section>

                <main class="container my-5">
                    <div class="card shadow-lg">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Member Profile</h3>
                            <?php if ($user["role"] !== "partner"): ?>
                                <div class="d-flex gap-2">
                                    <a
                                        href="<?= BASE_URL . "members/$member[id]/edit" ?>"
                                        class="btn btn-primary btn-sm px-3"
                                    >
                                        <i class="bi bi-pencil me-2"></i>
                                        Edit
                                    </a>
                                    <a
                                        class="btn btn-danger btn-sm px-3"
                                        href="<?= BASE_URL . "members/$member[id]/delete" ?>"
                                        onclick="return confirm('Are you sure you want to delete this member?')"
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
                                        src="<?= BASE_URL . htmlspecialchars($member['member_image_url']) ?>" 
                                        class="img-fluid rounded-circle mb-4"
                                        alt="Profile Picture"
                                        style="width: 200px; height: 200px; object-fit: cover;"
                                    >
                                    <h4 class="mb-2">
                                        <?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>
                                    </h4>
                                    <span class="badge <?= $member['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $member['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </div>

                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="fw-bold">Username</label>
                                            <p><?= htmlspecialchars($member['username']) ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Birth Date</label>
                                            <p><?= htmlspecialchars($member['birth_date']) ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Phone</label>
                                            <p><?= htmlspecialchars($member['phone']) ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Email</label>
                                            <p><?= htmlspecialchars($member['email']) ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold">Member Since</label>
                                            <p><?= htmlspecialchars($member['created_at']) ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="fw-bold mb-2">Identity Document</label>
                                            <img
                                                src="<?= BASE_URL . htmlspecialchars($member['identity_image_url']) ?>"
                                                class="img-fluid rounded"
                                                alt="Identity Document"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>

                <?php if ($haveFullAccess): ?>
                    <div class="container my-5">
                        <?php $this->renderCards() ?>
                        <?php $this->renderCreateModal() ?>
                    </div>
                <?php endif ?>

                <?php if ($haveFullAccess): ?>
                    <div class="container my-5">
                        <?= $this->discountsTable->renderHtml() ?>
                    </div>
                <?php endif ?>
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
                                            $username = $this->data['member']['username'];
                                            $firstName = $this->data['member']['first_name'];
                                            $lastName = $this->data['member']['last_name'];
                                            $role = 'member';
                                            $cardType = strtolower($card['type']['type']);
                                            $cardFee = $card['type']['fee'];
                                            $cardExpirationDate = $card['expiration_date'];
                                            $cardQrcodeImageUrl = $card['qrcode_image_url'];
                                            $isExpired = strtotime($cardExpirationDate) < time();
                                        ?>

                                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                            <div class="d-flex justify-content-center">
                                                <div class="membership-card">
                                                    <div class="card-content">
                                                        <div class="card-header-content d-flex justify-content-between">
                                                            <div>
                                                                <h5 class="card-title"><?= ucfirst($cardType) ?> Card</h5>
                                                                <p class="member-name">
                                                                    <?= "$firstName $lastName" ?>
                                                                </p>
                                                                <span class="role-badge"><?= ucfirst($role) ?></span>
                                                            </div>
                                                            <div class="fee-display">
                                                                <div class="fee-amount"><?= number_format($cardFee, 2) ?></div>
                                                                <div class="fee-label">Annual Fee</div>
                                                            </div>
                                                        </div>

                                                        <div class="card-details">
                                                            <div class="details-left">
                                                                <div class="detail-item">
                                                                    <div class="detail-label">Membership ID</div>
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
        $memberId = $this->data["member"]['id'];
        $action = "members/$memberId/cards/create";

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
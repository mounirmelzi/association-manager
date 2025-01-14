<?php

namespace App\Views\Pages;

use App\Models\User;
use App\Views\Components\Input;
use App\Models\CardType;

class MemberDetails extends Page
{
    private Input $cardTypeInput;

    public function __construct(array $data = []) {
        parent::__construct($data);

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

        ?>
            <body class="bg-light">
                <div class="container my-5">
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
                </div>

                <?php if ($haveFullAccess): ?>
                    <div class="container my-5">
                        <?php $this->renderCards() ?>
                        <?php $this->renderCreateModal() ?>
                    </div>
                <?php endif ?>
            </body>
        <?php
    }

    private function renderCards(): void
    {
        ?>
            <div class="subscription-card-container">
                <?php if (empty($this->data['cards'])): ?>
                    <div class="subscription-card-container">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <p class="empty-state-text">No subscription cards found</p>
                            <button
                                type="button"
                                class="add-card-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#create-modal"
                            >
                                <i class="bi bi-plus-lg"></i>
                            </button>
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
                                <button
                                    class="card-nav-btn"
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#create-modal"
                                >
                                    <i class="bi bi-plus-lg"></i>
                                </button>
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
                                                                <div class="fee-amount"><?= number_format($cardFee, 2) ?> €</div>
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
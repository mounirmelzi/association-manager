<?php

namespace App\Views\Pages;

use App\Models\User;

class MemberDetails extends Page
{
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

                <script src="<?= BASE_URL ?>js/libs/jquery-3.7.1.min.js" defer></script>
                <script src="<?= BASE_URL ?>js/libs/bootstrap.min.js" defer></script>
            </head>
        <?php
    }

    #[\Override]
    protected function body(): void
    {
        $user = User::current();
        $member = $this->data["member"];

        ?>
            <body class="bg-light">
                <div class="container py-5">
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
            </body>
        <?php
    }
}
?>
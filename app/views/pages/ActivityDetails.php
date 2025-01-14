<?php

namespace App\Views\Pages;

use App\Models\User;
use App\Models\Volunteering;

class ActivityDetails extends Page {
    #[\Override]
    protected function head(): void
    {
        ?>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Activity Details</title>

                <link href="<?= BASE_URL ?>css/libs/bootstrap.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>assets/icons/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">

                <script src="<?= BASE_URL ?>js/libs/jquery-3.7.1.min.js" defer></script>
                <script src="<?= BASE_URL ?>js/libs/bootstrap.min.js" defer></script>
            </head>
        <?php
    }

    #[\Override]
    protected function body(): void {
        $activity = $this->data['activity'];
        $user = User::current();

        if ($user !== null) {
            $volunteeringModel = new Volunteering();
            $volunteering = $volunteeringModel->getByUserIdAndActivityId($user['id'], $activity['id']);
            $volunteerStatus = ($volunteering !== null) ? ($volunteering['is_valid'] ? 'validated' : 'pending') : '';
        }

        ?>
            <body>
                <div class="container my-5">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h1 class="h3 mb-0">
                                    <?= htmlspecialchars($activity['title']) ?>
                                </h1>
                                <?php if ($user !== null): ?>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <div>
                                                <a
                                                    href="<?= BASE_URL . "activities/$activity[id]/edit" ?>"
                                                    class="btn btn-primary btn-sm me-2"
                                                >
                                                    <i class="bi bi-pencil me-1"></i>
                                                    Edit
                                                </a>
                                                <a
                                                    href="<?= BASE_URL . "activities/$activity[id]/delete" ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this activity?')"
                                                >
                                                    <i class="bi bi-trash me-1"></i>
                                                    Delete
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <?php if (!$volunteerStatus): ?>
                                                <a
                                                    href="<?= BASE_URL . "activities/$activity[id]/volunteer" ?>"
                                                    class="btn btn-success btn-sm"
                                                >
                                                    <i class="bi bi-hand-index-thumb me-1"></i>
                                                    Volunteer
                                                </a>
                                            <?php else: ?>
                                                <div class="d-flex align-items-center gap-2">
                                                    <?php if ($volunteerStatus === 'pending'): ?>
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="bi bi-clock-history me-1"></i>
                                                            Pending Admin Validation
                                                        </span>
                                                        <a
                                                            href="<?= BASE_URL . "activities/$activity[id]/volunteer/cancel" ?>"
                                                            class="btn btn-outline-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to cancel your volunteer request?')"
                                                        >
                                                            <i class="bi bi-x-circle me-1"></i>
                                                            Cancel Request
                                                        </a>                                                 
                                                    <?php elseif ($volunteerStatus === 'validated'): ?>
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle me-1"></i>
                                                            Volunteer Request Approved
                                                        </span>
                                                    <?php endif ?>
                                                </div>
                                            <?php endif ?>
                                        <?php endif ?>
                                    </div>
                                <?php endif ?>
                            </div>

                            <div class="card shadow-sm">
                                <img 
                                    src="<?= BASE_URL . $activity['image_url'] ?>" 
                                    class="card-img-top"
                                    alt="<?= htmlspecialchars($activity['title']) ?>"
                                    style="height: 300px; object-fit: cover;"
                                >
                                <div class="card-body">
                                    <div class="mb-4">
                                        <h5 class="card-title mb-3">Description</h5>
                                        <p class="card-text">
                                            <?= htmlspecialchars($activity['description']) ?>
                                        </p>
                                    </div>

                                    <div class="border-top pt-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <i class="bi bi-calendar3 text-muted me-2"></i>
                                                    <span class="text-muted">Date:</span>
                                                    <strong>
                                                        <?= date('F j, Y', strtotime($activity['date'])) ?>
                                                    </strong>
                                                </div>
                                                <div>
                                                    <i class="bi bi-clock text-muted me-2"></i>
                                                    <span class="text-muted">Time:</span>
                                                    <strong>
                                                        <?= date('g:i A', strtotime($activity['date'])) ?>
                                                    </strong>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                                <span class="badge bg-dark">Activity #<?= $activity['id'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a
                                    href="<?= BASE_URL . "activities" ?>"
                                    class="btn btn-outline-secondary"
                                >
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Back to Activities
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
        <?php
    }
}
?>
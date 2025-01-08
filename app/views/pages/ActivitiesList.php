<?php

namespace App\Views\Pages;

use App\Views\Components\Table;
use App\Views\Components\Column;

class ActivitiesList extends Page
{
    private Table $activitiesTable;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->activitiesTable = new Table(
            title: 'Activities List',
            data: $this->data["activities"],
            config: [
                'minHeight' => "75vh",
                'createText' => 'Create New Activity',
                'createUrl' => BASE_URL . "activities/create",
                'theme' => 'dark',
            ],
            columns: [
                new Column(
                    label: 'Activity',
                    renderer: function($activity): void {
                        ?>
                            <div class="d-flex align-items-center">
                                <img
                                    src="<?= BASE_URL . $activity['image_url'] ?>"
                                    alt="<?= $activity['title'] ?> image"
                                    class="rounded me-3"
                                    width="60"
                                    height="40"
                                    style="object-fit: cover"
                                >
                                <div>
                                    <div class="fw-medium">
                                        <?= htmlspecialchars($activity['title']) ?>
                                    </div>
                                    <div class="text-muted small">
                                        <?=
                                            substr(
                                                htmlspecialchars($activity['description']),
                                                0,
                                                100
                                            ) . '...'
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Created At',
                    renderer: function($activity): void {
                        ?>
                            <div class="small">
                                <i class="bi bi-calendar text-muted me-2"></i>
                                <?= date('j F Y', strtotime($activity['date'])) ?>
                            </div>
                        <?php
                    }
                ),
                new Column(
                    label: 'Actions',
                    width: '100px',
                    align: 'end',
                    renderer: function($activity): void {
                        ?>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL . "activities/$activity[id]/edit" ?>">
                                            <i class="bi bi-pencil me-2"></i>
                                            Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= BASE_URL . "activities/$activity[id]" ?>">
                                            <i class="bi bi-eye me-2"></i>
                                            View Details
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a
                                            class="dropdown-item text-danger"
                                            href="<?= BASE_URL . "activities/$activity[id]/delete" ?>"
                                            onclick="return confirm('Are you sure you want to delete this activity?')"
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
                <title>Activities</title>

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
        ?>
            <body>
                <div class="container py-5">
                    <?= $this->activitiesTable->renderHtml() ?>
                </div>
            </body>
        <?php
    }
}

<?php

namespace App\Views\Pages;

class Error extends Page
{
    #[\Override]
    protected function head(): void
    {
    ?>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?= $this->data["error_code"] ?> Error Page</title>

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
    ?>
    <body class="bg-light">
        <div class="container-fluid min-vh-100">
            <div class="row min-vh-100">
                <div class="col-md-4 bg-danger d-none d-md-flex align-items-center justify-content-center">
                    <i class="bi bi-exclamation-triangle-fill text-white" style="font-size: 8rem;"></i>
                </div>
                
                <div class="col-md-8 d-flex align-items-center justify-content-center p-5">
                    <div class="text-center">
                        <h1 class="display-1 fw-bold text-danger mb-3"><?= $this->data["error_code"] ?></h1>
                        <p class="lead text-secondary mb-4 fw-medium"><?= $this->data["error_message"] ?></p>
                        <div class="d-flex gap-3 justify-content-center">
                            <a href="<?= BASE_URL ?>" class="btn btn-danger btn-lg px-4 rounded-pill">
                                <i class="bi bi-house-fill me-2"></i>Return Home
                            </a>
                            <button onclick="history.back()" class="btn btn-outline-secondary btn-lg px-4 rounded-pill">
                                <i class="bi bi-arrow-left me-2"></i>Go Back
                            </button>
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
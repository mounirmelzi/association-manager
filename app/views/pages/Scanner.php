<?php

namespace App\Views\Pages;

use App\Models\Navbar as NavbarModel;
use App\Views\Components\Navbar as NavbarComponent;

class Scanner extends Page {
    #[\Override]
    protected function head(): void
    {
        ?>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Scanner</title>

            <link href="<?= BASE_URL ?>css/libs/bootstrap.min.css" rel="stylesheet">
            <link href="<?= BASE_URL ?>assets/icons/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
            <link href="<?= BASE_URL ?>css/pages/scanner.css" rel="stylesheet">
            
            <script src="<?= BASE_URL ?>js/libs/jquery-3.7.1.min.js" defer></script>
            <script src="<?= BASE_URL ?>js/libs/bootstrap.bundle.min.js" defer></script>
            <script src="<?= BASE_URL ?>js/libs/html5-qrcode.min.js" defer></script>
            <script src="<?= BASE_URL ?>js/pages/scanner.js" defer></script>
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
                    <h2 class="text-center mb-4">User Card Scanner</h2>

                    <div class="row justify-content-center mb-4">
                        <div class="col-12 col-md-8">
                            <div id="qr-reader" style="width:500px"></div>
                            <div id="qr-reader-results" class="mt-3 text-center"></div>
                        </div>
                    </div>

                    <div class="or-divider">
                        <span class="bg-white px-3">OR</span>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-12 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Manual Username Entry</h5>
                                    <form id="manual-form" method="POST" action="process-user.php">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
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
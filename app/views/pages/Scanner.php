<?php

namespace App\Views\Pages;

use App\Utils\Request;
use App\Models\Navbar as NavbarModel;
use App\Views\Components\Navbar as NavbarComponent;
use App\Views\Components\Form;
use App\Views\Components\Input;

class Scanner extends Page {
    private Form $manualForm;

    public function __construct(array $data = []) {
        parent::__construct($data);

        $this->manualForm = new Form(
            id: "scan-form",
            action: BASE_URL . Request::url(),
            config: [
                'title' => 'Manual User Scanner',
                'subtitle' => 'Enter the user information',
                'submitText' => 'Scan',
                'card' => false,
            ],
            inputComponents: [
                new Input(
                    name: 'card_type_id',
                    value: $this->data["values"]["card_type_id"] ?? null,
                    error: $this->data["errors"]["card_type_id"] ?? null,
                    config: [
                        'type' => 'select',
                        'options' => array_map(
                            function ($cardType) {
                                return [
                                    'name' => "[$cardType[id]] - $cardType[type]",
                                    'value' => $cardType['id']
                                ];
                            },
                            $this->data['cardTypes']
                        ),
                        'icon' => 'credit-card',
                        'placeholder' => 'Card Type',
                        'label' => 'Card Type',
                    ]
                ),
                new Input(
                    name: 'username',
                    value: $this->data["values"]["username"] ?? null,
                    error: $this->data["errors"]["username"] ?? null,
                    config: [
                        'icon' => 'person-badge',
                        'placeholder' => 'Username',
                        'label' => 'Username',
                    ]
                ),
            ],
        );
    }

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

                <main class="container py-5">
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
                                    <?= $this->manualForm->renderHtml() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </body>
        <?php
    }
}
?>
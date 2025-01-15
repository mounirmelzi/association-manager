<?php

namespace App\Views\Pages;

use App\Utils\Request;
use App\Views\Components\Form;
use App\Views\Components\Input;

class DiscountForm extends Page
{
    private Form $discountForm;

    public function __construct(array $data = []) {
        parent::__construct($data);

        $this->discountForm = new Form(
            id: "discount-form",
            action: BASE_URL . Request::url(),
            config: [
                'title' => 'Create Discount',
                'subtitle' => 'Enter your discount',
                'submitText' => 'Create',
                'card' => false,
            ],
            inputComponents: [
                new Input(
                    name: 'amount',
                    value: $this->data["values"]["amount"] ?? null,
                    error: $this->data["errors"]["amount"] ?? null,
                    config: [
                        'type' => 'number',
                        'icon' => 'coin',
                        'placeholder' => 'Amount',
                        'label' => 'Amount',
                    ]
                ),
                new Input(
                    name: 'description',
                    value: $this->data["values"]["description"] ?? null,
                    error: $this->data["errors"]["description"] ?? null,
                    config: [
                        'type' => 'textarea',
                        'icon' => 'card-text',
                        'placeholder' => 'Description',
                        'label' => 'Description',
                    ]
                ),
            ]
        );
    }

    #[\Override]
    protected function head(): void {
        ?>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Create Discount</title>

                <link href="<?= BASE_URL ?>css/libs/bootstrap.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>assets/icons/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">

                <script src="<?= BASE_URL ?>js/libs/jquery-3.7.1.min.js" defer></script>
                <script src="<?= BASE_URL ?>js/libs/bootstrap.min.js" defer></script>
            </head>
        <?php
    }

    #[\Override]
    protected function body(): void {
        ?>
            <body>
                <div class="container py-5">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <?php $this->discountForm->renderHtml() ?>
                        </div>
                    </div>
                </div>
            </body>
        <?php
    }
}

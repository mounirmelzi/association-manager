<?php

namespace App\Views\Pages;

use App\Utils\Request;
use App\Views\Components\Form;
use App\Views\Components\Input;

class PaymentForm extends Page
{
    private Form $paymentForm;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->paymentForm = new Form(
            id: "payment-form",
            action: BASE_URL . Request::url(),
            config: [
                'title' => 'Payment Form',
                'subtitle' => 'Enter your information',
                'submitText' => 'Save Changes',
                'card' => false,
            ],
            inputComponents: [
                new Input(
                    name: 'type',
                    value: $this->data["values"]["type"] ?? null,
                    error: $this->data["errors"]["type"] ?? null,
                    config: [
                        'type' => 'select',
                        'options' => [
                            ['name' => "Donation", 'value' => "donation"],
                            ['name' => "Registration Fee", 'value' => "registration_fee"],
                        ],
                        'icon' => 'tag',
                        'placeholder' => 'Select your payment type',
                        'label' => 'Payment Type',
                    ]
                ),
                new Input(
                    name: 'receipt_image',
                    value: null,
                    error: $this->data["errors"]["receipt_image"] ?? null,
                    config: [
                        'type' => 'file',
                        'icon' => 'image',
                        'label' => 'Payment Receipt',
                    ]
                ),
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
                <title>Payment Form</title>

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
            <body>
                <div class="container py-5">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <?php $this->paymentForm->renderHtml() ?>
                        </div>
                    </div>
                </div>
            </body>
        <?php
    }
}
?>
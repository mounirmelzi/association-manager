<?php

namespace App\Views\Pages;

use App\Utils\Request;
use App\Views\Components\Form;
use App\Views\Components\Input;

class HelpTypesForm extends Page {
    private Form $typeForm;

    public function __construct(array $data = []) {
        parent::__construct($data);

        $this->typeForm = new Form(
            id: "help-type-form",
            action: BASE_URL . Request::url(),
            config: [
                'title' => $this->data['title'],
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
                        'icon' => 'person-wheelchair',
                        'placeholder' => 'Type',
                        'label' => 'Type',
                    ]
                ),
                new Input(
                    name: 'attachments_description',
                    value: $this->data["values"]["attachments_description"] ?? null,
                    error: $this->data["errors"]["attachments_description"] ?? null,
                    config: [
                        'type' => 'textarea',
                        'icon' => 'card-text',
                        'placeholder' => 'Description',
                        'label' => 'Description',
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
                <title><?= $this->data['title'] ?></title>

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
                            <?php $this->typeForm->renderHtml() ?>
                        </div>
                    </div>
                </div>
            </body>
        <?php
    }
}

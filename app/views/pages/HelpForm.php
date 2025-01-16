<?php

namespace App\Views\Pages;

use App\Utils\Request;
use App\Views\Components\Form;
use App\Views\Components\Input;

class HelpForm extends Page
{
    private Form $helpForm;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->helpForm = new Form(
            id: "help-form",
            action: BASE_URL . Request::url(),
            config: [
                'title' => 'Help Request Form',
                'subtitle' => 'Submit your help request',
                'submitText' => 'Submit Request',
                'card' => false,
            ],
            inputComponents: [
                new Input(
                    name: 'help_type_id',
                    value: $this->data["values"]["help_type_id"] ?? null,
                    error: $this->data["errors"]["help_type_id"] ?? null,
                    config: [
                        'type' => 'select',
                        'options' => array_map(
                            function ($helpType) {
                                return [
                                    'name' => "[$helpType[id]] - $helpType[type]",
                                    'value' => $helpType['id']
                                ];
                            },
                            $this->data['helpTypes']
                        ),
                        'icon' => 'question-circle',
                        'placeholder' => 'Select help request type',
                        'label' => 'Help Type',
                    ]
                ),
                new Input(
                    name: 'description',
                    value: $this->data["values"]["description"] ?? null,
                    error: $this->data["errors"]["description"] ?? null,
                    config: [
                        'type' => 'textarea',
                        'icon' => 'file-text',
                        'placeholder' => 'Describe your request in detail...',
                        'label' => 'Description',
                    ]
                ),
                new Input(
                    name: 'attachments',
                    value: null,
                    error: $this->data["errors"]["attachments"] ?? null,
                    config: [
                        'type' => 'file',
                        'icon' => 'file-earmark-zip',
                        'label' => 'Attachments (.zip or .rar file)',
                        'accept' => '.zip,.rar',
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
                <title>Help Request Form</title>

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
                            <?php $this->helpForm->renderHtml() ?>
                        </div>
                    </div>
                </div>
            </body>
        <?php
    }
}
?>
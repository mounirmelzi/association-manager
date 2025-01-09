<?php

namespace App\Views\Pages;

use App\Utils\Request;
use App\Views\Components\Form;
use App\Views\Components\Input;

class NewsForm extends Page {
    private Form $newsForm;

    public function __construct(array $data = []) {
        parent::__construct($data);

        $inputComponents = [
            new Input(
                name: 'title',
                value: $this->data["values"]["title"] ?? null,
                error: $this->data["errors"]["title"] ?? null,
                config: [
                    'icon' => 'building',
                    'placeholder' => 'Title',
                    'label' => 'Title',
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
        ];

        $createInputComponents = [
            new Input(
                name: 'image',
                value: null,
                error: $this->data["errors"]["image"] ?? null,
                config: [
                    'type' => 'file',
                    'icon' => 'image',
                    'placeholder' => 'Image',
                    'label' => 'Image',
                ]
            ),
        ];

        $editInputComponents = [
            new Input(
                name: 'image',
                value: null,
                error: $this->data["errors"]["image"] ?? null,
                config: [
                    'type' => 'file',
                    'icon' => 'image',
                    'placeholder' => 'Image',
                    'label' => 'Image',
                    'required' => false,
                ]
            ),
        ];

        $action = $this->data['action'];
        if ($action === "create") {
            $inputComponents = array_merge($inputComponents, $createInputComponents);
        } else if ($action === "edit") {
            $inputComponents = array_merge($inputComponents, $editInputComponents);
        }

        $this->newsForm = new Form(
            id: "news-form",
            action: BASE_URL . Request::url(),
            inputComponents: $inputComponents,
            config: [
                'title' => $this->data['title'],
                'subtitle' => 'Enter your information',
                'submitText' => 'Save Changes',
                'card' => false,
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
            <body class="bg-light">
                <div class="container py-5">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <?php if (isset($this->data["values"]["image_url"])): ?>
                                <div class="text-center mb-4">
                                    <img
                                        src="<?= BASE_URL . htmlspecialchars($this->data["values"]["image_url"]) ?>"
                                        class="rounded"
                                        alt="News Image"
                                        style="max-width: 300px; max-height: 300px; object-fit: cover;"
                                    >
                                </div>
                            <?php endif ?>
                            <?php $this->newsForm->renderHtml() ?>
                        </div>
                    </div>
                </div>
            </body>
        <?php
    }
}

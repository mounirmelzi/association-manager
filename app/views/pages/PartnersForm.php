<?php

namespace App\Views\Pages;

use App\Utils\Request;
use App\Views\Components\Form;
use App\Views\Components\Input;

class PartnersForm extends Page
{
    private Form $partnersForm;

    public function __construct(array $data = []) {
        parent::__construct($data);

        $inputComponents = [
            new Input(
                name: 'name',
                value: $this->data["values"]["name"] ?? null,
                error: $this->data["errors"]["name"] ?? null,
                config: [
                    'icon' => 'building',
                    'placeholder' => 'Name',
                    'label' => 'Name',
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
            new Input(
                name: 'category',
                value: $this->data["values"]["category"] ?? null,
                error: $this->data["errors"]["category"] ?? null,
                config: [
                    'icon' => 'tag',
                    'placeholder' => 'Business Category',
                    'label' => 'Business Category',
                ]
            ),
            new Input(
                name: 'address',
                value: $this->data["values"]["address"] ?? null,
                error: $this->data["errors"]["address"] ?? null,
                config: [
                    'icon' => 'geo-alt',
                    'placeholder' => 'Physical Address',
                    'label' => 'Address',
                ]
            ),
            new Input(
                name: 'email',
                value: $this->data["values"]["email"] ?? null,
                error: $this->data["errors"]["email"] ?? null,
                config: [
                    'type' => 'email',
                    'icon' => 'envelope',
                    'placeholder' => 'Business Email',
                    'label' => 'Email Address',
                ]
            ),
            new Input(
                name: 'phone',
                value: $this->data["values"]["phone"] ?? null,
                error: $this->data["errors"]["phone"] ?? null,
                config: [
                    'type' => 'tel',
                    'icon' => 'telephone',
                    'placeholder' => 'Business Phone',
                    'label' => 'Phone Number',
                ]
            )
        ];

        $createInputComponents = [
            new Input(
                name: 'logo',
                value: null,
                error: $this->data["errors"]["logo"] ?? null,
                config: [
                    'type' => 'file',
                    'icon' => 'image',
                    'placeholder' => 'Logo',
                    'label' => 'Logo',
                ]
            ),
            new Input(
                name: 'username',
                value: $this->data["values"]["username"] ?? null,
                error: $this->data["errors"]["username"] ?? null,
                config: [
                    'icon' => 'person-circle',
                    'placeholder' => 'Login Username',
                    'label' => 'Username',
                ]
            ),
            new Input(
                name: 'password',
                value: $this->data["values"]["password"] ?? null,
                error: $this->data["errors"]["password"] ?? null,
                config: [
                    'type' => 'password',
                    'icon' => 'shield-lock',
                    'placeholder' => 'Create Password',
                    'label' => 'Password',
                ]
            ),
            new Input(
                name: 'confirm_password',
                value: $this->data["values"]["confirm_password"] ?? null,
                error: $this->data["errors"]["confirm_password"] ?? null,
                config: [
                    'type' => 'password',
                    'icon' => 'shield-check',
                    'placeholder' => 'Confirm Password',
                    'label' => 'Confirm Password',
                ]
            ),
        ];

        $editInputComponents = [
            new Input(
                name: 'logo',
                value: null,
                error: $this->data["errors"]["logo"] ?? null,
                config: [
                    'type' => 'file',
                    'icon' => 'image',
                    'placeholder' => 'Logo',
                    'label' => 'Logo',
                    'required' => false,
                ]
            ),
            new Input(
                name: 'old_password',
                value: $this->data["values"]["old_password"] ?? null,
                error: $this->data["errors"]["old_password"] ?? null,
                config: [
                    'type' => 'password',
                    'icon' => 'shield',
                    'placeholder' => 'Current Password',
                    'label' => 'Current Password',
                    'required' => false,
                ]
            ),
            new Input(
                name: 'new_password',
                value: $this->data["values"]["new_password"] ?? null,
                error: $this->data["errors"]["new_password"] ?? null,
                config: [
                    'type' => 'password',
                    'icon' => 'shield-lock',
                    'placeholder' => 'New Password',
                    'label' => 'New Password',
                    'required' => false,
                ]
            ),
            new Input(
                name: 'confirm_password',
                value: $this->data["values"]["confirm_password"] ?? null,
                error: $this->data["errors"]["confirm_password"] ?? null,
                config: [
                    'type' => 'password',
                    'icon' => 'shield-check',
                    'placeholder' => 'Confirm New Password',
                    'label' => 'Confirm New Password',
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

        $this->partnersForm = new Form(
            id: "partners-form",
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
    protected function head(): void {
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
    protected function body(): void {
        ?>
            <body class="bg-light">
                <div class="container py-5">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <?php if (isset($this->data["values"]["logo_url"])): ?>
                                <div class="text-center mb-4">
                                    <img
                                        src="<?= BASE_URL . htmlspecialchars($this->data["values"]["logo_url"]) ?>"
                                        class="rounded-circle"
                                        alt="Profile Picture"
                                        style="width: 150px; height: 150px; object-fit: cover;"
                                    >
                                </div>
                            <?php endif ?>
                            <?php $this->partnersForm->renderHtml() ?>
                        </div>
                    </div>
                </div>
            </body>
        <?php
    }
}

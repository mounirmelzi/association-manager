<?php

namespace App\Views\Pages;

use App\Models\User;
use App\Utils\Request;
use App\Views\Components\Form;
use App\Views\Components\Input;

class MemberEdit extends Page
{
    private Form $editForm;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $inputComponents = [
            new Input(
                name: 'first_name',
                value: $this->data["values"]["first_name"] ?? null,
                error: $this->data["errors"]["first_name"] ?? null,
                config: [
                    'icon' => 'person',
                    'placeholder' => 'First Name',
                    'label' => 'First Name',
                ]
            ),
            new Input(
                name: 'last_name',
                value: $this->data["values"]["last_name"] ?? null,
                error: $this->data["errors"]["last_name"] ?? null,
                config: [
                    'icon' => 'person',
                    'placeholder' => 'Last Name',
                    'label' => 'Last Name',
                ]
            ),
            new Input(
                name: 'email',
                value: $this->data["values"]["email"] ?? null,
                error: $this->data["errors"]["email"] ?? null,
                config: [
                    'type' => 'email',
                    'icon' => 'envelope',
                    'placeholder' => 'Email',
                    'label' => 'Email',
                ]
            ),
            new Input(
                name: 'phone',
                value: $this->data["values"]["phone"] ?? null,
                error: $this->data["errors"]["phone"] ?? null,
                config: [
                    'type' => 'tel',
                    'icon' => 'telephone',
                    'placeholder' => 'Phone',
                    'label' => 'Phone',
                ]
            ),
            new Input(
                name: 'birth_date',
                value: $this->data["values"]["birth_date"] ?? null,
                error: $this->data["errors"]["birth_date"] ?? null,
                config: [
                    'type' => 'date',
                    'icon' => 'calendar',
                    'placeholder' => 'Birth Date',
                    'label' => 'Birth Date',
                ]
            ),
            new Input(
                name: 'member_image',
                value: null,
                error: $this->data["errors"]["member_image"] ?? null,
                config: [
                    'type' => 'file',
                    'icon' => 'image',
                    'placeholder' => 'Profile Photo',
                    'label' => 'Profile Photo',
                    'required' => false,
                ]
            ),
            new Input(
                name: 'old_password',
                value: $this->data["values"]["old_password"] ?? null,
                error: $this->data["errors"]["old_password"] ?? null,
                config: [
                    'type' => 'password',
                    'icon' => 'key',
                    'placeholder' => 'Old Password',
                    'label' => 'Old Password',
                    'required' => false,
                ]
            ),
            new Input(
                name: 'new_password',
                value: $this->data["values"]["new_password"] ?? null,
                error: $this->data["errors"]["new_password"] ?? null,
                config: [
                    'type' => 'password',
                    'icon' => 'key-fill',
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
                    'icon' => 'key-fill',
                    'placeholder' => 'Confirm New Password',
                    'label' => 'Confirm New Password',
                    'required' => false,
                ]
            )
        ];

        $user = User::current();
        if ($user["role"] === "admin") {
            array_unshift($inputComponents, new Input(
                name: 'is_active',
                value: $this->data["values"]["is_active"] ?? null,
                error: $this->data["errors"]["is_active"] ?? null,
                config: [
                    'type' => 'checkbox',
                    'label' => 'Account Status (Is Active)',
                    'required' => false
                ]
            ));
        }

        $this->editForm = new Form(
            id: "edit-form",
            action: BASE_URL . Request::url(),
            inputComponents: $inputComponents,
            config: [
                'title' => 'Edit Profile',
                'subtitle' => 'Update your information',
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
                <title>Edit Profile</title>

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
                            <?php if (isset($this->data["values"]["member_image_url"])): ?>
                                <div class="text-center mb-4">
                                    <img
                                        src="<?= BASE_URL . htmlspecialchars($this->data["values"]["member_image_url"]) ?>" 
                                        class="rounded-circle" 
                                        alt="Profile Picture"
                                        style="width: 150px; height: 150px; object-fit: cover;"
                                    >
                                </div>
                            <?php endif ?>
                            <?php $this->editForm->renderHtml(); ?>
                        </div>
                    </div>
                </div>
            </body>
        <?php
    }
}
?>
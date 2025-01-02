<?php

namespace App\Views\Pages;

use App\Utils\Request;
use App\Views\Components\Form;
use App\Views\Components\Input;

class Register extends Page
{
    private Form $registerForm;

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->registerForm = new Form(
            id: "register-form",
            action: BASE_URL . Request::url(),
            config: [
                'title' => 'Create Account',
                'subtitle' => 'Join our community now',
                'submitText' => 'Register',
                'card' => false,
            ],
            inputComponents: [
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
                    name: 'username',
                    value: $this->data["values"]["username"] ?? null,
                    error: $this->data["errors"]["username"] ?? null,
                    config: [
                        'icon' => 'person-badge',
                        'placeholder' => 'Username',
                        'label' => 'Username',
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
                    value: $this->data["values"]["member_image"] ?? null,
                    error: $this->data["errors"]["member_image"] ?? null,
                    config: [
                        'type' => 'file',
                        'icon' => 'image',
                        'placeholder' => 'Profile Photo',
                        'label' => 'Profile Photo',
                    ]
                ),
                new Input(
                    name: 'identity_image',
                    value: $this->data["values"]["identity_image"] ?? null,
                    error: $this->data["errors"]["identity_image"] ?? null,
                    config: [
                        'type' => 'file',
                        'icon' => 'card-image',
                        'placeholder' => 'Identity Document',
                        'label' => 'Identity Document',
                    ]
                ),
                new Input(
                    name: 'password',
                    value: $this->data["values"]["password"] ?? null,
                    error: $this->data["errors"]["password"] ?? null,
                    config: [
                        'type' => 'password',
                        'icon' => 'key',
                        'placeholder' => 'Password',
                        'label' => 'Password',
                    ]
                ),
                new Input(
                    name: 'confirm_password',
                    value: $this->data["values"]["confirm_password"] ?? null,
                    error: $this->data["errors"]["confirm_password"] ?? null,
                    config: [
                        'type' => 'password',
                        'icon' => 'key-fill',
                        'placeholder' => 'Confirm Password',
                        'label' => 'Confirm Password',
                    ]
                )
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

                <title>Register</title>

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
                        <div class="col-md-4 bg-primary d-none d-md-flex align-items-center justify-content-center">
                            <i class="bi bi-person-plus-fill text-white" style="font-size: 8rem;"></i>
                        </div>
                        
                        <div class="col-md-8 d-flex align-items-center justify-content-center p-5">
                            <div style="max-width: 500px; width: 100%;">
                                <?php $this->registerForm->renderHtml(); ?>
                                
                                <div class="mt-3 text-center">
                                    <p class="mb-0">
                                        Already have an account? 
                                        <a href="<?= BASE_URL ?>login" class="text-primary text-decoration-none">Login</a>
                                    </p>
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
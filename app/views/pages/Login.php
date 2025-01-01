<?php

namespace App\Views\Pages;

use App\Utils\Request;
use App\Views\Components\Form;
use App\Views\Components\Input;

class Login extends Page
{
    private Form $loginForm;

    public function __construct(array $data = []) {
        parent::__construct($data);

        $this->loginForm = new Form(
            id: "login-form",
            action: BASE_URL . Request::url(),
            config: [
                'title' => 'Welcome Back',
                'subtitle' => 'Please login to continue',
                'submitText' => 'Login',
            ],
            inputComponents: [
                new Input(
                    name: 'username',
                    value: $this->data["values"]["username"] ?? null,
                    error: $this->data["errors"]["username"] ?? null,
                    config: [
                        'icon' => 'person',
                    ]
                ),
                new Input(
                    name: 'password',
                    value: $this->data["values"]["password"] ?? null,
                    error: $this->data["errors"]["password"] ?? null,
                    config: [
                        'type' => 'password',
                        'icon' => 'key',
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

                <title>Login</title>

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
                            <i class="bi bi-shield-lock-fill text-white" style="font-size: 8rem;"></i>
                        </div>
                        
                        <div class="col-md-8 d-flex align-items-center justify-content-center p-5">
                            <div style="max-width: 400px; width: 100%;">
                                <?php $this->loginForm->renderHtml(); ?>
                                
                                <div class="mt-3 text-center">
                                    <p class="mb-0">
                                        Don't have an account? 
                                        <a href="<?= BASE_URL ?>register" class="text-primary text-decoration-none">Register</a>
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
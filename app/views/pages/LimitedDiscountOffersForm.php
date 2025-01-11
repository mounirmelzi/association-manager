<?php

namespace App\Views\Pages;

use App\Utils\Request;
use App\Views\Components\Form;
use App\Views\Components\Input;

class LimitedDiscountOffersForm extends Page {
    private Form $discountsForm;

    public function __construct(array $data = []) {
        parent::__construct($data);

        $this->discountsForm = new Form(
            id: "limited-discount-offers-form",
            action: BASE_URL . Request::url(),
            config: [
                'title' => $this->data['title'],
                'subtitle' => 'Enter your information',
                'submitText' => 'Save Changes',
                'card' => false,
            ],
            inputComponents: [
                new Input(
                    name: 'partner_id',
                    value: $this->data["values"]["partner_id"] ?? null,
                    error: $this->data["errors"]["partner_id"] ?? null,
                    config: [
                        'type' => 'select',
                        'options' => array_map(
                            function ($partner) {
                                return [
                                    'name' => "[$partner[id]] - $partner[name] ($partner[category])",
                                    'value' => $partner['id'],
                                ];
                            },
                            $this->data['partners']
                        ),
                        'icon' => 'person',
                        'placeholder' => 'Partner',
                        'label' => 'Partner',
                    ]
                ),
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
                            $this->data['card_types']
                        ),
                        'icon' => 'credit-card',
                        'placeholder' => 'Card Type',
                        'label' => 'Card Type',
                    ]
                ),
                new Input(
                    name: 'percentage',
                    value: $this->data["values"]["percentage"] ?? null,
                    error: $this->data["errors"]["percentage"] ?? null,
                    config: [
                        'type' => 'number',
                        'icon' => 'tag',
                        'placeholder' => 'Percentage',
                        'label' => 'Percentage',
                    ]
                ),
                new Input(
                    name: 'start_date',
                    value: $this->data["values"]["start_date"] ?? null,
                    error: $this->data["errors"]["start_date"] ?? null,
                    config: [
                        'type' => 'datetime-local',
                        'icon' => 'calendar-date',
                        'placeholder' => 'Start',
                        'label' => 'Start',
                    ]
                ),
                new Input(
                    name: 'end_date',
                    value: $this->data["values"]["end_date"] ?? null,
                    error: $this->data["errors"]["end_date"] ?? null,
                    config: [
                        'type' => 'datetime-local',
                        'icon' => 'calendar-date',
                        'placeholder' => 'End',
                        'label' => 'End',
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
                            <?php $this->discountsForm->renderHtml() ?>
                        </div>
                    </div>
                </div>
            </body>
        <?php
    }
}
?>
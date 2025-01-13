<?php

namespace App\Views\Pages;

use App\Models\Navbar as NavbarModel;
use App\Views\Components\Navbar as NavbarComponent;
use App\Views\Components\Card;

class Dashboard extends Page
{
    #[\Override]
    protected function head(): void
    {
        ?>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Dashboard</title>

                <link href="<?= BASE_URL ?>css/libs/bootstrap.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>assets/icons/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
                
                <script src="<?= BASE_URL ?>js/libs/jquery-3.7.1.min.js" defer></script>
                <script src="<?= BASE_URL ?>js/libs/bootstrap.bundle.min.js" defer></script>
            </head>
        <?php
    }

    #[\Override]
    protected function body(): void
    {
        $navbarModel = new NavbarModel();
        $navbarComponent = new NavbarComponent(['items' => $navbarModel->all()]);

        ?>
            <body>
                <section class="sticky-top">
                    <?php $navbarComponent->renderHtml() ?>
                </section>

                <div class="container py-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3 mb-0">Admin Dashboard</h1>
                    </div>

                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                        <?php foreach ($this->data['cards'] as $card): ?>
                            <?= $this->renderCard($card) ?>
                        <?php endforeach ?>
                    </div>
                </div>
            </body>
        <?php
    }

    private function renderCard(array $card): void
    {
        $cardComponent = new Card(
            title: $card['title'],
            config: [
                'description' => 'Click to manage',
                'link' => $card['link'],
                'icon' => $card['icon'],
                'bgColor' => '#f0f9ff',
                'borderColor' => '#dbeafe',
                'iconColor' => '#1d4ed8',
                'titleColor' => '#1e3a8a',
                'textColor' => '#334155',
                'chevronColor' => '#1d4ed8',
            ]
        );

        ?>
            <div class="col">
                <?php $cardComponent->renderHtml() ?>
            </div>
        <?php
    }
}
?>
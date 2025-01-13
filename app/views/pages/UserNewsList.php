<?php

namespace App\Views\Pages;

use App\Models\Navbar as NavbarModel;
use App\Views\Components\Navbar as NavbarComponent;
use App\Views\Components\Card;

class UserNewsList extends Page
{
    private array $cards = [];

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        foreach ($this->data["news"] as $news) {
            $this->cards[] = new Card(
                title: $news['title'],
                config: [
                    'description' => $news['description'],
                    'imageUrl' => BASE_URL . $news['image_url'],
                    'link' => BASE_URL . "news/{$news['id']}",
                    'subTitle' => date('j F Y', strtotime($news['date'])),
                    'width' => '100%',
                    'height' => '100%',
                    'bgColor' => '#ffffff',
                    'borderColor' => '#eaeaea',
                    'textColor' => '#666666',
                    'titleColor' => '#333333',
                    'chevronColor' => '#0066cc',
                    'icon' => 'newspaper' // Added news-specific icon
                ]
            );
        }
    }

    #[\Override]
    protected function head(): void
    {
        ?>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Latest News</title>

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
                    <h1 class="mb-4">Latest News</h1>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php foreach ($this->cards as $card): ?>
                            <div class="col">
                                <?php $card->renderHtml(); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </body>
        <?php
    }
}
<?php

namespace App\Views\Pages;

use App\Models\User;
use App\Models\Diaporama as DiaporamaModel;
use App\Models\Navbar as NavbarModel;
use App\Views\Components\Diaporama as DiaporamaComponent;
use App\Views\Components\Navbar as NavbarComponent;
use App\Views\Components\Card;
use App\Views\Components\Table;
use App\Views\Components\Column;
use DateTime;

class Home extends Page
{
    #[\Override]
    protected function head(): void
    {
        ?>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Home</title>

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
        $user = User::current();
        $profileUrl =  BASE_URL . match ($user['role'] ?? '') {
            "admin" => 'admins/' . $user['id'],
            "member" => 'members/' . $user['id'],
            "partner" => 'partners/' . $user['id'],
            default => 'login',
        };

        $diaporamaModel = new DiaporamaModel();
        $diaporamaComponent = new DiaporamaComponent(['slides' => $diaporamaModel->all()]);

        $navbarModel = new NavbarModel();
        $navbarComponent = new NavbarComponent(['items' => $navbarModel->all()]);

        ?>
            <body>
                <header class="container container-fluid pt-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <a href="<?= BASE_URL . 'home' ?>" class="text-decoration-none">
                                <h1 class="text-primary">El Mountada</h1>
                            </a>
                        </div>
                        <div class="col-md-6 text-end d-flex justify-content-end align-items-center gap-3">
                            <?php $this->renderSocialLinks() ?>

                            <?php if ($user === null): ?>
                                <a href="<?= BASE_URL . 'login' ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    Login
                                </a>
                                <a href="<?= BASE_URL . 'register' ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-person-plus me-2"></i>
                                    Register
                                </a>
                            <?php else: ?>
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-person-circle me-2"></i>
                                        <?= htmlspecialchars($user['username']) ?>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <?php if ($user['role'] === 'admin'): ?>
                                            <li>
                                                <a class="dropdown-item" href="<?= BASE_URL . 'dashboard' ?>">
                                                    <i class="bi bi-person me-2"></i>
                                                    Dashboard
                                                </a>
                                            </li>
                                        <?php else: ?>
                                            <li>
                                                <a class="dropdown-item" href="<?= $profileUrl ?>">
                                                    <i class="bi bi-person me-2"></i>
                                                    Profile
                                                </a>
                                            </li>
                                        <?php endif ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="<?= BASE_URL . 'logout' ?>">
                                                <i class="bi bi-box-arrow-right me-2"></i>
                                                Logout
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </header>

                <section class="container">
                    <?php $diaporamaComponent->renderHtml() ?>
                </section>

                <section class="mb-5 sticky-top">
                    <?php $navbarComponent->renderHtml() ?>
                </section>

                <main class="container">
                    <div class="container py-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h3 mb-0">News and Announcements</h1>
                            <a href="<?= BASE_URL . 'news' ?>">View All</a>
                        </div>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                            <?php foreach ($this->data['news'] as $news): ?>
                                <?= $this->renderNewsCard($news) ?>
                            <?php endforeach ?>
                        </div>
                    </div>

                    <hr>

                    <?= $this->renderPartnersLogos() ?>

                    <hr>

                    <div class="container py-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="h3 mb-0">Activities and Events</h1>
                            <a href="<?= BASE_URL . 'activities' ?>">View All</a>
                        </div>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                            <?php foreach ($this->data['activities'] as $activity): ?>
                                <?= $this->renderActivityCard($activity) ?>
                            <?php endforeach ?>
                        </div>
                    </div>
                </main>

                <section class="container py-5">
                    <?php $this->renderDiscounts() ?>
                </section>

                <section class="container py-5">
                    <?php $this->renderLimitedDiscounts() ?>
                </section>

                <footer class="container container-fluid py-5">
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <?php $this->renderSocialLinks() ?>
                        </div>
                    </div>
                </footer>
            </body>
        <?php
    }

    private function renderDiscounts(): void
    {
        $data = $this->data['discounts'];

        $columns = [
            new Column(label: 'Partner', key: 'partner_name'),
            new Column(label: 'Category', key: 'partner_category'),
            new Column(label: 'Address', key: 'partner_address'),
            new Column(label: 'Card Type', key: 'card_type'),
            new Column(label: 'Discount Percentage', key: 'percentage'),
        ];

        $table = new Table(
            'Discounts',
            $data,
            $columns,
        );

        $table->renderHtml();
    }

    private function renderLimitedDiscounts(): void
    {
        $data = $this->data['limitedDiscounts'];

        $columns = [
            new Column(label: 'Partner', key: 'partner_name'),
            new Column(label: 'Category', key: 'partner_category'),
            new Column(label: 'Address', key: 'partner_address'),
            new Column(label: 'Card Type', key: 'card_type'),
            new Column(label: 'Discount Percentage', key: 'percentage'),
            new Column(label: 'Start Date', key: 'start_date', renderer: function($data) {
                return (new DateTime($data['start_date']))->format('Y-m-d H:i:s');
            }),
            new Column(label: 'End Date', key: 'end_date', renderer: function($data) {
                return (new DateTime($data['end_date']))->format('Y-m-d H:i:s');
            }),
        ];

        $table = new Table(
            'Limited Discounts',
            $data,
            $columns,
        );

        $table->renderHtml();
    }

    private function renderSocialLinks(): void
    {
        ?>
            <div class="social-links">
                <?php foreach ($this->data['socials'] as $social): ?>
                    <a 
                        href="<?= $social['link'] ?>" 
                        class="social-link me-2 btn btn-primary"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <i class="m-2 bi bi-<?= $social['icon'] ?>"></i>
                    </a>
                <?php endforeach ?>
            </div>
        <?php
    }

    private function renderNewsCard(array $news): void
    {
        $cardComponent = new Card(
            title: $news['title'],
            config: [
                'description' => $news['description'],
                'subTitle' => date('j F Y, h:m', strtotime($news['date'])),
                'imageUrl' => $news['image_url'],
            ]
        );

        ?>
            <div class="col">
                <?php $cardComponent->renderHtml() ?>
            </div>
        <?php
    }

    private function renderActivityCard(array $activity): void
    {
        $cardComponent = new Card(
            title: $activity['title'],
            config: [
                'description' => $activity['description'],
                'subTitle' => date('j F Y, h:m', strtotime($activity['date'])),
                'imageUrl' => $activity['image_url'],
            ]
        );

        ?>
            <div class="col">
                <?php $cardComponent->renderHtml() ?>
            </div>
        <?php
    }

    private function renderPartnersLogos(): void
    {
        ?>
            <div id="logoCarousel" class="container my-5">
                <h2>Our Partners</h2>
                <div class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php $isFirstItem = true; foreach (array_chunk($this->data['logos'], 8) as $chunk): ?>
                            <div class="carousel-item <?= $isFirstItem ? 'active' : '' ?>">
                                <div class="d-flex justify-content-center gap-3">
                                    <?php foreach ($chunk as $logo): ?>
                                        <img
                                            src="<?= BASE_URL . $logo ?>"
                                            class="img-fluid"
                                            alt="Logo"
                                        >
                                    <?php endforeach ?>
                                </div>
                            </div>
                        <?php $isFirstItem = false; endforeach ?>
                    </div>
                </div>
            </div>

            <style>
                #logoCarousel .carousel-inner {
                    max-height: 100px;
                }
                #logoCarousel .carousel-item {
                    height: 100px;
                }
                #logoCarousel img {
                    max-height: 100px;
                    max-width: 100px;
                }
            </style>
        <?php
    }
}
?>
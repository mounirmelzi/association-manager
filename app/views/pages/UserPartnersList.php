<?php

namespace App\Views\Pages;

use App\Models\Navbar as NavbarModel;
use App\Views\Components\Navbar as NavbarComponent;

class UserPartnersList extends Page
{
    private array $categorizedPartners = [];

    public function __construct(array $data = [])
    {
        parent::__construct($data);

        foreach ($this->data["partners"] as $partner) {
            $this->categorizedPartners[$partner['category']][] = $partner;
        }

        ksort($this->categorizedPartners);
    }

    #[\Override]
    protected function head(): void
    {
        ?>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Our Partners</title>

                <link href="<?= BASE_URL ?>css/libs/bootstrap.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>assets/icons/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>css/pages/user-partners-list.css" rel="stylesheet">

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
                    <div class="row mb-4">
                        <div class="col">
                            <h1>Our Partners</h1>
                            <p class="text-muted">Discover the organizations that work with us</p>
                        </div>
                    </div>

                    <?php foreach ($this->categorizedPartners as $category => $partners): ?>
                        <section id="<?= urlencode($category) ?>" class="category-section mb-5">
                            <h2 class="h3 mb-4"><?= htmlspecialchars($category) ?></h2>
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                                <?php foreach ($partners as $partner): ?>
                                    <div class="col">
                                        <a 
                                            href="<?= BASE_URL . "partners/{$partner['id']}" ?>" 
                                            class="text-decoration-none"
                                        >
                                            <div class="card h-100 partner-card shadow-sm">
                                                <div class="card-body text-center">
                                                    <img 
                                                        src="<?= BASE_URL . $partner['logo_url'] ?>" 
                                                        alt="<?= htmlspecialchars($partner['name']) ?>'s logo"
                                                        class="mb-3"
                                                    >
                                                    <h5 class="card-title text-dark">
                                                        <?= htmlspecialchars($partner['name']) ?>
                                                    </h5>
                                                    <div class="contact-info">
                                                        <div>
                                                            <i class="bi bi-envelope me-2"></i>
                                                            <?= htmlspecialchars($partner['email']) ?>
                                                        </div>
                                                        <div>
                                                            <i class="bi bi-telephone me-2"></i>
                                                            <?= htmlspecialchars($partner['phone']) ?>
                                                        </div>
                                                        <div>
                                                            <i class="bi bi-geo-alt me-2"></i>
                                                            <?= htmlspecialchars($partner['address']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </section>
                    <?php endforeach ?>
                </div>
            </body>
        <?php
    }
}
?>
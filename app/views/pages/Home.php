<?php

namespace App\Views\Pages;

use App\Views\Components\Diaporama as DiaporamaComponent;
use App\Views\Components\Navbar as NavbarComponent;

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
                
                <script src="<?= BASE_URL ?>js/libs/jquery-3.7.1.min.js" defer></script>
                <script src="<?= BASE_URL ?>js/libs/bootstrap.min.js" defer></script>
            </head>
        <?php
    }

    #[\Override]
    protected function body(): void
    {
        $diaporama = new DiaporamaComponent(['slides' => $this->data['diaporamaSlides']]);
        $navbar = new NavbarComponent(['items' => $this->data['navbarItems']]);

        ?>
            <body>
                <?php $navbar->renderHtml() ?>

                <div class="container my-5">
                    <?php $diaporama->renderHtml() ?>
                </div>
            </body>
        <?php
    }
}
?>
<?php

namespace App\Views\Pages;

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
    ?>
    <body>
        <h1><?= $this->data["message"] ?></h1>
    </body>
    <?php
    }
}
?>
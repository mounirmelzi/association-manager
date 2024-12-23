<?php

namespace App\Views\Pages;

use App\Core\View;

abstract class Page extends View
{
    abstract protected function head(): void;
    abstract protected function body(): void;

    #[\Override]
    public function renderHtml(): void
    {
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <?= $this->head() ?>
        <?= $this->body() ?>
    </html>
    <?php
    }
}
?>
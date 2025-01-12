<?php

namespace App\Views\Components;

class Navbar extends Component
{
    #[\Override]
    public function renderHtml(): void
    {
        ?>
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container">
                    <button
                        class="navbar-toggler mx-auto" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarMain" aria-controls="navbarMain"
                        aria-expanded="false" aria-label="Toggle navigation"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse justify-content-center" id="navbarMain">
                        <ul class="navbar-nav">
                            <?php foreach ($this->data['items'] as $item): ?>
                                <li class="nav-item">
                                    <a
                                        class="nav-link" 
                                        href="<?= BASE_URL . $item['url'] ?>"
                                    >
                                        <?= ucfirst(htmlspecialchars($item['name'])) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </nav>
        <?php
    }
}
?>
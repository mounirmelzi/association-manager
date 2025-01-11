<?php

namespace App\Views\Pages;

use App\Utils\Request;
use App\Views\Components\Table;
use App\Views\Components\Column;
use App\Views\Components\Input;

class Navbar extends Page
{
    private Table $itemsTable;
    private Input $nameInput;
    private Input $urlInput;

    public function __construct(array $data = []) {
        parent::__construct($data);

        $this->itemsTable = new Table(
            title: '',
            data: $this->data['items'],
            config: [
                'minHeight' => '50vh',
                'theme' => 'dark',
            ],
            columns: [
                new Column(label: 'Name', key: 'name'),
                new Column(label: 'URL', key: 'url'),
                new Column(
                    label: 'Actions',
                    width: '100px',
                    align: 'end',
                    renderer: function($item): void {
                        ?>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a
                                            class="dropdown-item text-danger"
                                            href="<?= BASE_URL . "navbar/$item[id]/delete" ?>"
                                            onclick="return confirm('Are you sure you want to delete this navbar item?')"
                                        >
                                            <i class="bi bi-trash me-2"></i>
                                            Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php
                    }
                ),
            ],
        );

        $this->nameInput = new Input(
            name: 'name',
            config: [
                'icon' => 'list',
                'label' => 'Name',
            ]
        );

        $this->urlInput = new Input(
            name: 'url',
            config: [
                'icon' => 'link',
                'label' => 'Url',
            ]
        );
    }

    #[\Override]
    protected function head(): void
    {
        ?>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Navbar</title>

                <link href="<?= BASE_URL ?>css/libs/bootstrap.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>assets/icons/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">

                <script src="<?= BASE_URL ?>js/libs/jquery-3.7.1.min.js" defer></script>
                <script src="<?= BASE_URL ?>js/libs/bootstrap.bundle.min.js" defer></script>
            </head>
        <?php
    }

    #[\Override]
    protected function body(): void {
        ?>
            <body>
                <div class="container py-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3">Navigation Items</h1>
                        <button 
                            type="button"
                            class="btn btn-dark"
                            data-bs-toggle="modal"
                            data-bs-target="#nav-item-modal"
                        >
                            <i class="bi bi-plus-lg me-2"></i>
                            Add Navigation Item
                        </button>
                    </div>
                    <?= $this->itemsTable->renderHtml() ?>
                </div>
                <?php $this->modal() ?>
            </body>
        <?php
    }

    private function modal(): void {
        ?>
            <div class="modal fade" id="nav-item-modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Navigation Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="<?= BASE_URL . Request::url() ?>" method="POST">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <?= $this->nameInput->renderHtml() ?>
                                </div>
                                <div class="mb-3">
                                    <?= $this->urlInput->renderHtml() ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
    }
}
?>
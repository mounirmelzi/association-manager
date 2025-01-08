<?php

namespace App\Views\Pages;

use App\Utils\Request;
use App\Views\Components\Input;

class Diaporama extends Page
{
    private Input $imageInput;

    public function __construct(array $data = []) {
        parent::__construct($data);

        $this->imageInput = new Input(
            name: 'image',
            config: [
                'type' => 'file',
                'icon' => 'image',
                'placeholder' => 'Image',
                'label' => 'Choose Image',
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
                <title>Diaporama</title>

                <link href="<?= BASE_URL ?>css/libs/bootstrap.min.css" rel="stylesheet">
                <link href="<?= BASE_URL ?>assets/icons/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">

                <script src="<?= BASE_URL ?>js/libs/jquery-3.7.1.min.js" defer></script>
                <script src="<?= BASE_URL ?>js/libs/bootstrap.bundle.min.js" defer></script>
            </head>
        <?php
    }

    #[\Override]
    protected function body(): void {
        $slides = $this->data['slides'];

        ?>
            <body>
                <div class="container py-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3">Diaporama Slides</h1>
                        <button 
                            type="button"
                            class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#upload-modal"
                        >
                            <i class="bi bi-plus-lg me-2"></i>
                            Add New Slide
                        </button>
                    </div>

                    <div class="row g-4">
                        <?php foreach ($slides as $slide): ?>
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <img
                                        src="<?= BASE_URL . $slide['image_url'] ?>"
                                        class="card-img-top"
                                        alt="Slide Image"
                                        style="height: 200px; object-fit: cover;"
                                    >
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-dark">Slide #<?= $slide['id'] ?></span>
                                            <a
                                                href="<?= BASE_URL . "diaporama/$slide[id]/delete" ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this slide?')"
                                            >
                                                <i class="bi bi-trash"></i>
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>

                    <?php if (empty($slides)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-images text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 mb-0">No slides found. Add some slides to get started!</p>
                        </div>
                    <?php endif ?>
                </div>

                <?php $this->renderUploadModal() ?>
            </body>
        <?php
    }

    private function renderUploadModal(): void {
        ?>
            <div class="modal fade" id="upload-modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload New Slide</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="<?= BASE_URL . Request::url() ?>" method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <?= $this->imageInput->renderHtml() ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php
    }
}

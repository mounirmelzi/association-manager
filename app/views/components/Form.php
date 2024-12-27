<?php

namespace App\Views\Components;

class Form extends Component
{
    private string $id;
    private string $action;
    private string $method;
    private array $inputComponents;
    private ?string $title;
    private ?string $subtitle;
    private bool $card;
    private ?string $submitText;
    private ?string $clearText;

    public function __construct(
        string $id,
        string $action,
        array $inputComponents,
        array $config = []
    ) {
        $this->id = $id;
        $this->action = $action;
        $this->method = $config['method'] ?? 'post';
        $this->inputComponents = $inputComponents;
        $this->title = $config['title'] ?? null;
        $this->subtitle = $config['subtitle'] ?? null;
        $this->card = $config['card'] ?? true;
        $this->submitText = $config['submitText'] ?? 'Submit';
        $this->clearText = $config['clearText'] ?? 'Clear';
    }

    private function renderHeader(): void
    {
        if ($this->title || $this->subtitle) {
            ?>
                <div class="text-center mb-4">
                    <?php if ($this->title): ?>
                        <h1 class="h3 fw-bold text-primary"><?= $this->title ?></h1>
                    <?php endif; ?>
                    <?php if ($this->subtitle): ?>
                        <p class="text-secondary"><?= $this->subtitle ?></p>
                    <?php endif; ?>
                </div>
            <?php
        }
    }

    private function renderInputs(): void
    {
        foreach ($this->inputComponents as $inputComponent) {
            $inputComponent->renderHtml();
        }
    }

    private function renderButtons(): void
    {
        ?>
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill">
                    <i class="bi bi-box-arrow-in-right me-2"></i><?= $this->submitText ?>
                </button>
                <button type="button" id="clear-form-button" class="btn btn-outline-secondary rounded-pill">
                    <i class="bi bi-x-lg me-2"></i><?= $this->clearText ?>
                </button>
                <script>
                    window.addEventListener('load', () => {
                        $(document).ready(() => {
                            const form = $('#<?= $this->id ?>');
                            form.on('click', '#clear-form-button', () => {
                                form.find('input, textarea').val('').removeClass('is-invalid');
                                form.find('.invalid-feedback').remove();
                            });
                        });
                    });
                </script>
            </div>
        <?php
    }

    public function renderHtml(): void
    {
        if ($this->card) {
            echo '<div class="card shadow-sm border-0 w-100">';
            echo '<div class="card-body p-4">';
        }

        $this->renderHeader();

        ?>
            <form id="<?= $this->id ?>" method="<?= $this->method ?>" action="<?= $this->action ?>">
                <?= $this->renderInputs() ?>
                <?= $this->renderButtons() ?>
            </form>
        <?php

        if ($this->card) {
            echo '</div>';
            echo '</div>';
        }
    }
}
?>
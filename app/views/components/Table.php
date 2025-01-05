<?php

namespace App\Views\Components;

class Table extends Component
{
    private string $title;
    private array $columns;
    private string $minHeight;
    private ?string $createText;
    private ?string $createUrl;
    private string $theme;

    public function __construct(
        string $title,
        array $data,
        array $columns,
        array $config = []
    ) {
        parent::__construct($data);

        $this->title = $title;
        $this->columns = $columns;
        $this->minHeight = $config['minHeight'] ?? "0px";
        $this->createText = $config['createText'] ?? null;
        $this->createUrl = $config['createUrl'] ?? null;
        $this->theme = $config['theme'] ?? 'primary';
    }

    public function renderHtml(): void
    {
        ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0"><?= $this->title ?></h1>
                <?php if ($this->createText && $this->createUrl): ?>
                    <a href="<?= $this->createUrl ?>" class="btn btn-<?= $this->theme ?>">
                        <i class="bi bi-plus-lg me-1"></i><?= $this->createText ?>
                    </a>
                <?php endif ?>
            </div>

            <div class="card shadow-sm">
                <div class="table-responsive" style="min-height: <?= $this->minHeight ?>">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <?php foreach ($this->columns as $column): ?>
                                    <th scope="col" <?= $column->width ? "style='width: {$column->width}'" : '' ?>>
                                        <?= $column->label ?>
                                    </th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->data as $row): ?>
                                <tr>
                                    <?php foreach ($this->columns as $column): ?>
                                        <?php
                                            $column->data = $row;
                                            $column->renderHtml();
                                        ?>
                                    <?php endforeach ?>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
    }
}
?>
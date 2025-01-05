<?php

namespace App\Views\Components;

class Card extends Component
{
    private string $title;
    private string $subTitle;
    private string $description;
    private string $width;
    private string $height;
    private string $imageUrl;
    private string $link;
    private string $icon;
    private string $bgColor;
    private string $borderColor;
    private string $iconColor;
    private string $titleColor;
    private string $textColor;
    private string $chevronColor;

    public function __construct(
        string $title,
        array $config = []
    ) {
        $this->title = $title;
        $this->subTitle = $config['subTitle'] ?? '';
        $this->description = $config['description'] ?? '';
        $this->width = $config['width'] ?? '18rem';
        $this->height = $config['height'] ?? 'auto';
        $this->imageUrl = $config['imageUrl'] ?? '';
        $this->link = $config['link'] ?? '';
        $this->icon = $config['icon'] ?? '';
        $this->bgColor = $config['bgColor'] ?? '#ffffff';
        $this->borderColor = $config['borderColor'] ?? '#dee2e6';
        $this->iconColor = $config['iconColor'] ?? '#0d6efd';
        $this->titleColor = $config['titleColor'] ?? '#212529';
        $this->textColor = $config['textColor'] ?? '#6c757d';
        $this->chevronColor = $config['chevronColor'] ?? '#0d6efd';
    }

    #[\Override]
    public function renderHtml(): void
    {
        ?>
            <?php if (!empty($this->link)): ?>
                <a 
                    href="<?= htmlspecialchars($this->link) ?>"
                    class="text-decoration-none" 
                    style="cursor: pointer"
                >
            <?php endif ?>

            <div
                class="card h-100 shadow-sm" 
                style="
                    width: <?= $this->width ?>;
                    height: <?= $this->height ?>;
                    background-color: <?= $this->bgColor ?>;
                    border-color: <?= $this->borderColor ?>;
                "
            >
                <?php if (!empty($this->imageUrl)): ?>
                    <img 
                        src="<?= htmlspecialchars($this->imageUrl) ?>"
                        class="card-img-top" 
                        alt="<?= htmlspecialchars($this->title) ?>"
                        style="object-fit: cover;"
                    >
                <?php endif ?>
                <div class="card-body d-flex flex-column align-items-start p-4">
                    <?php if (!empty($this->icon)): ?>
                        <i 
                            class="bi bi-<?= htmlspecialchars($this->icon) ?> fs-1 mb-3" 
                            style="color: <?= $this->iconColor ?>;">
                        </i>
                    <?php endif ?>
                    <h5 
                        class="card-title mb-2" 
                        style="color: <?= $this->titleColor ?>;">
                        <?= htmlspecialchars($this->title) ?>
                    </h5>
                    <?php if (!empty($this->subTitle)): ?>
                        <h6 
                            class="card-subtitle mb-2 text-muted" 
                            style="color: <?= $this->textColor ?>;">
                            <?= htmlspecialchars($this->subTitle) ?>
                        </h6>
                    <?php endif ?>
                    <?php if (!empty($this->description)): ?>
                        <p 
                            class="card-text" 
                            style="color: <?= $this->textColor ?>;">
                            <?= htmlspecialchars($this->description) ?>
                        </p>
                    <?php endif ?>
                </div>

                <?php if ((!empty($this->link)) && (empty($this->imageUrl))): ?>
                    <div 
                        class="position-absolute end-0 me-3 top-50 translate-middle-y"
                        style="color: <?= $this->chevronColor ?>;"
                    >
                        <i class="bi bi-chevron-right fs-4"></i>
                    </div>
                <?php endif ?>
            </div>

            <?php if (!empty($this->link)): ?>
                </a>
            <?php endif ?>
        <?php
    }
}

<?php

namespace App\Views\Components;

class Input extends Component
{
    private string $name;
    private mixed $value;
    private ?string $error;
    private string $type;
    private string $label;
    private bool $required;
    private ?string $icon;
    private string $placeholder;
    private ?int $rows;

    public function __construct(
        string $name,
        mixed $value = null,
        ?string $error = null,
        array $config = []
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->error = $error;
        $this->type = $config['type'] ?? 'text';
        $this->label = $config['label'] ?? ucfirst($name);
        $this->required = $config['required'] ?? true;
        $this->icon = $config['icon'] ?? null;
        $this->placeholder = $config['placeholder'] ?? '';
        $this->rows = $config['rows'] ?? 3;
    }

    public function renderHtml(): void
    {
        if ($this->type === 'hidden') {
            ?>
                <input 
                    type="hidden"
                    id="<?= $this->name ?>"
                    name="<?= $this->name ?>"
                    value="<?= htmlspecialchars((string)$this->value) ?>"
                >
            <?php
            return;
        }

        if ($this->type === 'checkbox') {
            ?>
                <div class="mb-3 form-check">
                    <label class="form-check-label" for="<?= $this->name ?>">
                        <?= $this->label ?>
                    </label>
                    <input 
                        type="checkbox"
                        class="form-check-input <?= $this->error ? 'is-invalid' : '' ?>"
                        id="<?= $this->name ?>"
                        name="<?= $this->name ?>"
                        value="<?= true ?>"
                        <?= $this->value ? 'checked' : '' ?>
                        <?= $this->required ? 'required' : '' ?>
                    >                    
                    <?php if ($this->error): ?>
                        <div class="invalid-feedback">
                            <?= htmlspecialchars($this->error) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php
            return;
        }

        ?>
            <div class="mb-3">
                <label for="<?= $this->name ?>" class="form-label"><?= $this->label ?></label>
                <div class="input-group">
                    <?php if ($this->icon): ?>
                        <span class="input-group-text"><i class="bi bi-<?= $this->icon ?>"></i></span>
                    <?php endif; ?>

                    <?php if ($this->type === 'textarea'): ?>
                        <textarea 
                            class="form-control <?= $this->error ? 'is-invalid' : '' ?>"
                            id="<?= $this->name ?>"
                            name="<?= $this->name ?>"
                            placeholder="<?= $this->placeholder ?>"
                            rows="<?= $this->rows ?>"
                            <?= $this->required ? 'required' : '' ?>
                        ><?= htmlspecialchars((string)$this->value) ?></textarea>
                    <?php else: ?>
                        <input 
                            type="<?= $this->type ?>"
                            class="form-control <?= $this->error ? 'is-invalid' : '' ?>"
                            id="<?= $this->name ?>"
                            name="<?= $this->name ?>"
                            placeholder="<?= $this->placeholder ?>"
                            value="<?= htmlspecialchars((string)$this->value) ?>"
                            <?= $this->required ? 'required' : '' ?>
                        >
                    <?php endif; ?>

                    <?php if ($this->error): ?>
                        <div class="invalid-feedback">
                            <?= htmlspecialchars($this->error) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php
    }
}
?>
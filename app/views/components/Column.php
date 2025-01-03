<?php

namespace App\Views\Components;

class Column extends Component
{
    public string $label;
    public ?string $key;
    public ?string $width;
    public ?string $align;
    public mixed $renderer;

    public function __construct(
        string $label,
        ?string $key = null,
        ?string $width = null,
        ?string $align = null,
        ?callable $renderer = null,
    ) {
        $this->label = $label;
        $this->key = $key;
        $this->width = $width;
        $this->align = $align;
        $this->renderer = $renderer;
    }

    public function renderHtml(): void
    {
        ?>
            <td class="align-middle <?= $this->align ? "text-{$this->align}" : "" ?>">
                <?= $this->renderer ? ($this->renderer)($this->data) : ($this->data[$this->key] ?? '') ?>
            </td>
        <?php
    }
}
?>
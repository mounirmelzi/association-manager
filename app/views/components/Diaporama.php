<?php

namespace App\Views\Components;

class Diaporama extends Component
{
    #[\Override]
    public function renderHtml(): void
    {
        $this->style();
        $this->script();

        ?>
            <div class="diaporama-container">
                <div class="diaporama">
                    <?php foreach ($this->data['slides'] as $slide): ?>
                        <img
                            src="<?= BASE_URL . $slide['image_url'] ?>"
                            alt="<?= "Slide $slide[id]" ?>"
                        >
                    <?php endforeach ?>
                </div>
            </div>
        <?php
    }

    private function style(): void
    {
        ?>
            <style>
                div.diaporama-container {
                    width: 100% - 10%;
                    height: 75vh;
                    overflow: hidden;
                    margin: 0 5%;
                }

                div.diaporama-container > div.diaporama {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100%;
                }

                div.diaporama-container > div.diaporama > img {
                    background-color: black;
                    box-sizing: border-box;
                    padding: 0 1%;
                }
            </style>
        <?php
    }

    private function script(): void
    {
        ?>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const diaporama = document.querySelector('.diaporama');
                    const slides = document.querySelectorAll('.diaporama img');
                    const slideCount = slides.length;

                    const diaporamaWidth = slideCount * 100;
                    const slideWidth = 100 / slideCount;
                    diaporama.style.width = `${diaporamaWidth}%`;
                    slides.forEach(slide => {
                        slide.style.width = `${slideWidth}%`;
                    });

                    const duration = slideCount * 5;
                    diaporama.style.animation = `slide ${duration}s infinite ease-in-out`;
                    const style = document.createElement('style');
                    let keyframes = `@keyframes slide {`;
                    for (let i = 0; i < slideCount; i++) {
                        const startPercent = (i * 100) / slideCount;
                        const endPercent = startPercent + (50 / slideCount);
                        const transformValue = -(i * (100 / slideCount));
                        keyframes += `
                            ${startPercent}% { transform: translateX(${transformValue}%); }
                            ${endPercent}% { transform: translateX(${transformValue}%); }
                        `;
                    }
                    keyframes += `100% { transform: translateX(${(1 - slideCount) * 100 / slideCount}%); }`;

                    diaporama.addEventListener('mouseenter', function () {
                        diaporama.style.animationPlayState = 'paused';
                    });

                    diaporama.addEventListener('mouseleave', function () {
                        diaporama.style.animationPlayState = 'running';
                    });

                    style.innerHTML = keyframes;
                    document.head.appendChild(style);
                });
            </script>
        <?php
    }
}

<?php

namespace App\Views\Components;

class Diaporama extends Component
{
    private array $options;

    public function __construct(array $data, array $options = [])
    {
        parent::__construct($data);

        $this->options = array_merge([
            'slideDuration' => 3,
            'transitionDuration' => 1.5,
            'showControls' => true,
            'showIndicators' => true,
            'autoplay' => true,
            'loop' => true
        ], $options);
    }

    #[\Override]
    public function renderHtml(): void
    {
        $carouselId = 'carousel-' . uniqid();

        ?>
            <div class="container my-4 px-0">
                <div 
                    id="<?= $carouselId ?>"
                    class="carousel slide"
                    data-bs-ride="carousel"
                    data-bs-interval="<?= $this->options['slideDuration'] * 1000 ?>"
                >
                    <?php if ($this->options['showIndicators']): ?>
                        <div class="carousel-indicators">
                            <?php foreach ($this->data['slides'] as $index => $slide): ?>
                                <button 
                                    type="button" 
                                    data-bs-target="#<?= $carouselId ?>" 
                                    data-bs-slide-to="<?= $index ?>" 
                                    <?= $index === 0 ? 'class="active" aria-current="true"' : '' ?>
                                    aria-label="Slide <?= $index + 1 ?>"
                                ></button>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                    <div class="carousel-inner">
                        <?php foreach ($this->data['slides'] as $index => $slide): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img 
                                    src="<?= htmlspecialchars(BASE_URL . $slide['image_url']) ?>" 
                                    class="d-block w-100" 
                                    alt="Slide <?= $index + 1 ?>"
                                    loading="lazy"
                                >
                            </div>
                        <?php endforeach ?>
                    </div>

                    <?php if ($this->options['showControls']): ?>
                        <button 
                            class="carousel-control-prev"
                            type="button"
                            data-bs-target="#<?= $carouselId ?>"
                            data-bs-slide="prev"
                        >
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button
                            class="carousel-control-next"
                            type="button"
                            data-bs-target="#<?= $carouselId ?>"
                            data-bs-slide="next"
                        >
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    <?php endif ?>
                </div>
            </div>

            <style>
                .carousel {
                    max-height: 70vh;
                    border-radius: 1rem;
                    overflow: hidden;
                }

                .carousel-item {
                    height: 70vh;
                    background-color: transparent;
                }

                .carousel-item img {
                    object-fit: cover;
                    height: 100%;
                    width: 100%;
                }

                .carousel-control-prev,
                .carousel-control-next {
                    width: 5%;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }

                .carousel:hover .carousel-control-prev,
                .carousel:hover .carousel-control-next {
                    opacity: 0.8;
                }

                .carousel-control-prev:hover,
                .carousel-control-next:hover {
                    opacity: 1;
                }

                .carousel-indicators {
                    margin-bottom: 1rem;
                }

                <?= "#$carouselId" ?> .carousel-item {
                    transition: transform <?= $this->options['transitionDuration'] ?>s ease-in-out;
                }

                @media (max-width: 768px) {
                    .carousel, .carousel-item {
                        height: 50vh;
                    }

                    .carousel-control-prev,
                    .carousel-control-next {
                        opacity: 0.8;
                    }
                }
            </style>
            
            <script>
                $(document).ready(function() {
                    const $carousel = $('#<?= $carouselId ?>');

                    const bsCarousel = new bootstrap.Carousel($carousel[0], {
                        interval: <?= $this->options['slideDuration'] * 1000 ?>,
                        wrap: <?= json_encode($this->options['loop']) ?>,
                        pause: 'hover'
                    });

                    if (!<?= json_encode($this->options['autoplay']) ?>) {
                        bsCarousel.pause();
                    }
                });
            </script>
        <?php
    }
}
?>
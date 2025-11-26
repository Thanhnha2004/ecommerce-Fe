<?php
function renderSliderSection($title, $products, $viewAllLink = '#')
{
    ?>
    <div class="mt-5 bg-white">
        <div class="container d-flex justify-content-between align-items-center p-3">
            <h4 class="text-danger fw-bold mb-0"><?= $title ?></h4>
            <a href="<?= $viewAllLink ?>" class="text-danger text-decoration-none fw-bold">XEM TẤT CẢ</a>
        </div>
        <div class="border-bottom border-1" style="border-color: #e7dfdfff !important;"></div>
        <div class="product-slider">
            <?php foreach ($products as $product): ?>
                <div class="p-2 product-item overflow-auto">
                    <div class="card border border-light text-center p-2 position-relative hover-border-red cursor-pointer">
                        <div class="position-relative">
                            <img src="http://localhost:8000/storage/<?= $product['image'] ?>" class="card-img-top img-fluid" />
                        </div>
                        <div class="card-body text-start p-2">
                            <div class="d-flex justify-content-start align-items-baseline mb-1">
                                <div class="fw-bold text-danger me-2"><?= $product['price'] ?></div>
                            </div>
                            <div class="text-uppercase fw-bold"><?= $product['brand'] ?></div>
                            <p class="card-text text-dark mt-1 mb-0" style="min-height: 40px;">
                                <?= $product['name'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $(document).ready(function () {
            // Khởi tạo TẤT CẢ sliders có class 'product-slider'
            $('.product-slider').slick({
                dots: false,
                infinite: true,
                speed: 500,
                arrows: true,
                slidesToShow: 4,
                slidesToScroll: 1,
                responsive: [
                    { breakpoint: 1200, settings: { slidesToShow: 4 } },
                    { breakpoint: 992, settings: { slidesToShow: 3 } },
                    { breakpoint: 768, settings: { slidesToShow: 2 } },
                    { breakpoint: 576, settings: { slidesToShow: 1 } }
                ]
            });
        });
    </script>
    <?php
}
?>
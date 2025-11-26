<?php
function renderBrandSection($title, $brands, $viewAllLink = '#')
{
    ?>
    <div class="mt-5 bg-white">
        <div class="container d-flex justify-content-between align-items-center p-3">
            <h4 class="text-danger fw-bold mb-0"><?= $title ?></h4>
            <a href="<?= $viewAllLink ?>" class="text-danger text-decoration-none fw-bold">XEM TẤT CẢ</a>
        </div>
        <div class="border-bottom border-1" style="border-color: #e7dfdfff !important;"></div>
        <div class="d-flex">
            <?php foreach ($brands as $brand): ?>
                <div class="col-2 d-flex brand flex-column justify-content-between align-items-center">
                    <a href="<?= $brand['link'] ?? '#' ?>" class="text-decoration-none"></a>
                    <img src="http://localhost:8000/storage/<?= $brand['image'] ?>" class="img-fluid" />
                    <p class="card-text text-dark mt-1 mb-0 fw-bold">
                        <?= $brand['brand_name'] ?>
                    </p>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
    <?php
}
?>
<?php
function renderProductCard($product, $cardClass = '') {
    ?>
    <div class="makeup-card position-relative <?= $cardClass ?>">
        <div class="border border-3 rounded mb-2" style="border-color: #9c27b0 !important;">
            <img src="http://localhost:8000/storage/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="img-fluid">
        </div>
        <div class="text-start">
            <div class="fw-bold text-danger mb-1">
                <?= formatPrice($product['price']) ?>
                <?php if (isset($product['old_price'])): ?>
                    <small class="text-muted text-decoration-line-through ms-2" style="font-size: 12px;">
                        <?= formatPrice($product['old_price']) ?>
                    </small>
                <?php endif; ?>
            </div>
            <div class="fw-bold text-uppercase mb-1" style="font-size: 13px;">
                <?= $product['brand'] ?>
            </div>
            <p class="text-dark mb-0" style="font-size: 13px; min-height: 40px;">
                <?= $product['name'] ?>
            </p>
        </div>
    </div>
    <?php
}
?>
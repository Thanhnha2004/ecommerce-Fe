<?php
require_once 'helpers/functions.php';
require_once 'components/product-card.php';
require_once 'components/product-section.php';
require_once 'components/slider-section.php';
require_once 'components/brand-section.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - Mỹ Phẩm Đơn Giản</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <link rel="stylesheet" href="../css/style.css">

    <style>
        /* Spinner chung */
        #main-spinner {
            padding: 40px 0;
        }
        #main-spinner .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>

<body class="bg-light">
    <?php include 'includes/header.php'; ?>

    <main class="container">

        <!-- Banner Section -->
        <div class="row mb-4">
            <div class="col-8">
                <img src="../index3.jpg" class="img-fluid w-100" />
            </div>
            <div class="col-4 d-flex flex-column justify-content-between">
                <img src="../index2.webp" class="img-fluid w-100 mb-2" />
                <img src="../index1.webp" class="img-fluid w-100" />
            </div>
        </div>

        <!-- Spinner chung dưới banner -->
        <div id="main-spinner" class="text-center">
            <div class="spinner-border text-danger" role="status"></div>
        </div>

        <!-- ========== HOT PRODUCTS ========== -->
        <div class="section-block" id="section-hot" style="display:none;">
            <?php renderSliderSection('SẢN PHẨM HOT', getHotProducts(5)); ?>
        </div>

        <!-- ========== BRAND SECTION ========== -->
        <div class="section-block" id="section-brand" style="display:none;">
            <?php renderBrandSection('THƯƠNG HIỆU NỔI BẬT', getBrands(6)); ?>
        </div>

        <!-- ========== MAKEUP SECTION ========== -->
        <div class="section-block" id="section-makeup" style="display:none;">
            <?php
            renderProductSection([
                'sidebarPosition' => 'left',
                'sidebarTitle' => 'TRANG ĐIỂM',
                'sidebarImage' => '../uploads/banner/Trang_diem.jpg',
                'tabs' => ['TRANG ĐIỂM MẶT', 'TRANG ĐIỂM MẮT'],
                'products' => getMakeupProducts(8)
            ]);
            ?>
        </div>

        <!-- ========== LIPSTICK SECTION ========== -->
        <div class="section-block" id="section-lipstick" style="display:none;">
            <?php
            renderProductSection([
                'sidebarPosition' => 'right',
                'sidebarTitle' => 'SON MÔI',
                'sidebarImage' => '../uploads/banner/Son_moi.jpg',
                'tabs' => ['SON THỎI', 'SON KEM', 'SON DƯỠNG', 'SON BÓNG'],
                'products' => getLipstickProducts(8)
            ]);
            ?>
        </div>

        <!-- ========== SKINCARE SECTION ========== -->
        <div class="section-block" id="section-skincare" style="display:none;">
            <?php
            renderProductSection([
                'sidebarPosition' => 'left',
                'sidebarTitle' => 'CHĂM SÓC DA',
                'sidebarImage' => '../uploads/banner/Cham_soc_da.jpg',
                'tabs' => ['LÀM SẠCH', 'DƯỠNG DA', 'MẶT NẠ', 'KEM CHỐNG NẮNG'],
                'products' => getSkincareProducts(8)
            ]);
            ?>
        </div>

    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // Khi trang đã load xong, ẩn spinner chung và hiển thị tất cả section
            $("#main-spinner").fadeOut(200, function() {
                $(".section-block").fadeIn(300);
            });
        });
    </script>

</body>
</html>

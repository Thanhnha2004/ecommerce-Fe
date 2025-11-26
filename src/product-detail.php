<?php
// Đường dẫn tuyệt đối từ thư mục src/
require_once __DIR__ . '/helpers/functions.php';

// Hàm helper để gọi API bằng cURL
function callAPI($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Cho localhost
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'response' => $response,
        'http_code' => $httpCode,
        'error' => $error
    ];
}

// Lấy ID sản phẩm từ URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($productId <= 0) {
    header('Location: index.php');
    exit;
}

// Gọi API để lấy chi tiết sản phẩm
$apiUrl = "http://localhost:8000/api/products/{$productId}";
$apiResult = callAPI($apiUrl);

if ($apiResult['http_code'] !== 200 || !$apiResult['response']) {
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lỗi - Beauty Lux</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php include 'includes/header.php'; ?>
        <div class="container mt-5">
            <div class="alert alert-danger">
                <h4><i class="fas fa-exclamation-triangle"></i> Không thể tải thông tin sản phẩm</h4>
                <p><strong>Product ID:</strong> <?= $productId ?></p>
                <p><strong>API URL:</strong> <?= $apiUrl ?></p>
                <p><strong>HTTP Code:</strong> <?= $apiResult['http_code'] ?></p>
                <?php if ($apiResult['error']): ?>
                    <p><strong>Error:</strong> <?= htmlspecialchars($apiResult['error']) ?></p>
                <?php endif; ?>
                <?php if ($debugMode && $apiResult['response']): ?>
                    <hr>
                    <p><strong>Response:</strong></p>
                    <pre><?= htmlspecialchars($apiResult['response']) ?></pre>
                <?php endif; ?>
            </div>
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại trang chủ
            </a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$data = json_decode($apiResult['response'], true);

// Xử lý nhiều format response khác nhau
$product = null;
if (isset($data['data'])) {
    $product = $data['data'];
} elseif (isset($data['product'])) {
    $product = $data['product'];
} elseif (is_array($data) && isset($data['id'])) {
    $product = $data;
}

if (!$product || !is_array($product) || !isset($product['name'])) {
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sản phẩm không tồn tại - Beauty Lux</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php include 'includes/header.php'; ?>
        <div class="container mt-5">
            <div class="alert alert-warning">
                <h4><i class="fas fa-info-circle"></i> Sản phẩm không tồn tại</h4>
                <p>Không tìm thấy sản phẩm với ID: <strong><?= $productId ?></strong></p>
                <?php if ($debugMode): ?>
                    <hr>
                    <p><strong>Raw Response:</strong></p>
                    <pre><?= print_r($data, true) ?></pre>
                <?php endif; ?>
            </div>
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại trang chủ
            </a>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Xử lý ảnh - hỗ trợ nhiều format
$mainImage = '../default.webp';
$allImages = [];

// Thử lấy ảnh từ nhiều nguồn
if (!empty($product['main_image'])) {
    $mainImage = "http://localhost:8000/storage/{$product['main_image']}";
} elseif (!empty($product['image'])) {
    $mainImage = "http://localhost:8000/storage/{$product['image']}";
} elseif (!empty($product['images']) && is_array($product['images']) && count($product['images']) > 0) {
    $firstImage = $product['images'][0];
    $mainImage = is_array($firstImage) 
        ? "http://localhost:8000/storage/{$firstImage['url']}"
        : "http://localhost:8000/storage/{$firstImage}";
}

// Xử lý tất cả ảnh
if (!empty($product['all_images']) && is_array($product['all_images'])) {
    $allImages = array_map(function($img) {
        return is_array($img) 
            ? "http://localhost:8000/storage/{$img['url']}"
            : "http://localhost:8000/storage/{$img}";
    }, $product['all_images']);
} elseif (!empty($product['images']) && is_array($product['images'])) {
    $allImages = array_map(function($img) {
        return is_array($img) 
            ? "http://localhost:8000/storage/{$img['url']}"
            : "http://localhost:8000/storage/{$img}";
    }, $product['images']);
}

// Nếu không có ảnh nào, dùng ảnh chính
if (empty($allImages)) {
    $allImages = [$mainImage];
}

// Xử lý các trường dữ liệu
$productName = $product['name'] ?? 'Sản phẩm';
$productBrand = $product['brand']['brand_name'] ?? $product['brand'] ?? 'Chưa xác định';
$productPrice = $product['price'] ?? 0;
$formattedPrice = $product['formatted_price'] ?? number_format($productPrice, 0, ',', '.') . 'đ';
$productDescription = $product['description'] ?? 'Đang cập nhật thông tin sản phẩm...';
$stockQuantity = $product['stock_quantity'] ?? $product['stock'] ?? 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($productName) ?> - Beauty Lux</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    
    <style>
        .product-gallery {
            position: sticky;
            top: 20px;
        }
        
        .main-image-container {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
            background: white;
            padding: 20px;
        }
        
        .main-image {
            width: 100%;
            height: 450px;
            object-fit: contain;
            cursor: zoom-in;
        }
        
        .thumbnail-gallery {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 5px 0;
        }
        
        .thumbnail {
            width: 80px;
            height: 80px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            object-fit: cover;
            flex-shrink: 0;
        }
        
        .thumbnail:hover,
        .thumbnail.active {
            border-color: #dc3545;
            transform: scale(1.05);
        }
        
        .product-info {
            background: white;
            padding: 30px;
            border-radius: 8px;
        }
        
        .product-brand {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        
        .product-name {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .product-price {
            font-size: 36px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 20px;
        }
        
        .stock-status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .in-stock {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .out-of-stock {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .quantity-input {
            display: flex;
            align-items: center;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .quantity-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: #f5f5f5;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.3s;
        }
        
        .quantity-btn:hover {
            background: #e0e0e0;
        }
        
        .quantity-value {
            width: 60px;
            height: 40px;
            border: none;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }
        
        .btn-add-cart {
            background: #dc3545;
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .btn-add-cart:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }
        
        .btn-buy-now {
            background: #333;
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .btn-buy-now:hover {
            background: #555;
            transform: translateY(-2px);
        }
        
        .product-description {
            background: white;
            padding: 30px;
            border-radius: 8px;
            margin-top: 30px;
        }
        
        .description-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #dc3545;
        }
        
        .product-specs {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin-top: 20px;
        }
        
        .spec-item {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .spec-item:last-child {
            border-bottom: none;
        }
        
        .spec-label {
            font-weight: 600;
            width: 180px;
            color: #666;
        }
        
        .spec-value {
            flex: 1;
            color: #333;
        }
        
        .breadcrumb {
            background: transparent;
            padding: 15px 0;
            margin-bottom: 20px;
        }
        
        .breadcrumb-item a {
            color: #666;
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            color: #dc3545;
        }
        
        .breadcrumb-item.active {
            color: #333;
        }
    </style>
</head>

<body class="bg-light">
    <?php include 'includes/header.php'; ?>

    <main class="container my-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="#">Sản phẩm</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($productName) ?></li>
            </ol>
        </nav>

        <div class="row">
            <!-- Gallery Section -->
            <div class="col-md-5">
                <div class="product-gallery">
                    <div class="main-image-container">
                        <img id="mainImage" 
                             src="<?= htmlspecialchars($mainImage) ?>" 
                             alt="<?= htmlspecialchars($productName) ?>" 
                             class="main-image"
                             onerror="this.src='../default.webp'">
                    </div>
                    
                    <?php if (count($allImages) > 1): ?>
                    <div class="thumbnail-gallery">
                        <?php foreach ($allImages as $index => $image): ?>
                        <img src="<?= htmlspecialchars($image) ?>" 
                             alt="Thumbnail <?= $index + 1 ?>" 
                             class="thumbnail <?= $index === 0 ? 'active' : '' ?>"
                             onclick="changeMainImage('<?= htmlspecialchars($image) ?>', this)"
                             onerror="this.src='../default.webp'">
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info Section -->
            <div class="col-md-7">
                <div class="product-info">
                    <div class="product-brand">
                        <i class="fas fa-star text-warning"></i>
                        <?= htmlspecialchars($productBrand) ?>
                    </div>
                    
                    <h1 class="product-name"><?= htmlspecialchars($productName) ?></h1>
                    
                    <div class="product-price">
                        <?= htmlspecialchars($formattedPrice) ?>
                    </div>
                    
                    <div>
                        <?php if ($stockQuantity): ?>
                        <span class="stock-status in-stock">
                            <i class="fas fa-check-circle me-2"></i>Còn hàng (<?= $stockQuantity ?> sản phẩm)
                        </span>
                        <?php else: ?>
                        <span class="stock-status out-of-stock">
                            <i class="fas fa-times-circle me-2"></i>Hết hàng
                        </span>
                        <?php endif; ?>
                    </div>
                    
                    <hr class="my-4">
                    
                    <?php if ($stockQuantity): ?>
                    <div class="quantity-selector">
                        <label class="fw-bold">Số lượng:</label>
                        <div class="quantity-input">
                            <button class="quantity-btn" onclick="decreaseQuantity()">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" 
                                   id="quantityInput" 
                                   class="quantity-value" 
                                   value="1" 
                                   min="1" 
                                   max="<?= $stockQuantity ?>"
                                   readonly>
                            <button class="quantity-btn" onclick="increaseQuantity()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-3 mt-4">
                        <button class="btn-add-cart" onclick="addToCart()">
                            <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ
                        </button>
                        <button class="btn-buy-now" onclick="buyNow()">
                            <i class="fas fa-bolt me-2"></i>Mua ngay
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Sản phẩm hiện đang hết hàng. Vui lòng quay lại sau!
                    </div>
                    <?php endif; ?>
                    
                    <div class="mt-4 pt-4 border-top">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-truck text-danger me-3 fs-5"></i>
                            <span>Miễn phí vận chuyển cho đơn hàng từ 300.000đ</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-shield-alt text-danger me-3 fs-5"></i>
                            <span>Cam kết 100% hàng chính hãng</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-sync-alt text-danger me-3 fs-5"></i>
                            <span>Đổi trả trong vòng 7 ngày</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description -->
        <div class="product-description">
            <h2 class="description-title">
                <i class="fas fa-info-circle text-danger me-2"></i>
                Mô tả sản phẩm
            </h2>
            
            <div class="description-content">
                <?= nl2br(htmlspecialchars($productDescription)) ?>
            </div>
            
            <div class="product-specs">
                <h5 class="fw-bold mb-3">Thông số sản phẩm</h5>
                <div class="spec-item">
                    <div class="spec-label">Thương hiệu:</div>
                    <div class="spec-value"><?= htmlspecialchars($productBrand) ?></div>
                </div>
                <div class="spec-item">
                    <div class="spec-label">Tình trạng:</div>
                    <div class="spec-value">
                        <?= $stockQuantity ? 'Còn hàng' : 'Hết hàng' ?>
                    </div>
                </div>
                <div class="spec-item">
                    <div class="spec-label">Giá:</div>
                    <div class="spec-value text-danger fw-bold">
                        <?= htmlspecialchars($formattedPrice) ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const maxQuantity = <?= $stockQuantity ?>;
        const productId = <?= $productId ?>;
        
        function changeMainImage(imageUrl, thumbnail) {
            document.getElementById('mainImage').src = imageUrl;
            
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            thumbnail.classList.add('active');
        }
        
        function increaseQuantity() {
            const input = document.getElementById('quantityInput');
            const currentValue = parseInt(input.value);
            if (currentValue < maxQuantity) {
                input.value = currentValue + 1;
            }
        }
        
        function decreaseQuantity() {
            const input = document.getElementById('quantityInput');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        }
        
        function addToCart() {
            const quantity = document.getElementById('quantityInput').value;
            alert(`Đã thêm ${quantity} sản phẩm vào giỏ hàng!`);
        }
        
        function buyNow() {
            const quantity = document.getElementById('quantityInput').value;
            alert(`Mua ${quantity} sản phẩm. Chuyển đến trang thanh toán...`);
        }
    </script>
</body>
</html>
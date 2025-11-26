<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { background-color: #f5f5f5; }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #1e293b;
            color: white;
            overflow-y: auto;
        }
        .sidebar .brand {
            padding: 1.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc3545;
        }
        .sidebar nav a {
            display: block;
            padding: 0.875rem 1.5rem;
            color: white;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .sidebar nav a:hover { background-color: #334155; }
        .sidebar nav a.active {
            background-color: #334155;
            border-left: 4px solid #3b82f6;
        }
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }
        .header {
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 2rem;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }
        .stock-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
        }
        .in-stock {
            background-color: #d1fae5;
            color: #065f46;
        }
        .out-of-stock {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .low-stock {
            background-color: #fef3c7;
            color: #92400e;
        }
        .image-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .image-gallery img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            border: 2px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>

    <div class="main-content">
        <div class="header">
            <p class="text-muted mb-0">Admin / Quản lý sản phẩm</p>
            <h2 class="mb-0">Quản lý Sản phẩm</h2>
        </div>

        <div class="p-4">
            <div id="alert-container"></div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <input type="text" id="search-input" class="form-control" placeholder="Tìm sản phẩm...">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" onclick="loadProducts()">
                                <i class="fas fa-search me-2"></i>Tìm kiếm
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-success w-100" onclick="showAddModal()">
                                <i class="fas fa-plus me-2"></i>Thêm
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Danh sách Sản phẩm</h5>
                </div>
                <div class="card-body">
                    <div id="loading" class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                    </div>
                    <div id="products-table" class="d-none">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="80">Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Thương hiệu</th>
                                    <th>Loại</th>
                                    <th>Giá</th>
                                    <th>Tồn kho</th>
                                    <th width="180">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="products-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modal-title">Thêm Sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="product-form">
                        <input type="hidden" id="product-id">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="product-name" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Giá <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="product-price" min="0" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tồn kho <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="product-stock" min="0" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Thương hiệu <span class="text-danger">*</span></label>
                                <select class="form-select" id="product-brand" required>
                                    <option value="">Chọn thương hiệu</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Loại sản phẩm <span class="text-danger">*</span></label>
                                <select class="form-select" id="product-category" required>
                                    <option value="">Chọn loại</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" id="product-description" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mã vạch</label>
                                <input type="text" class="form-control" id="product-barcode">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Xuất xứ</label>
                                <input type="text" class="form-control" id="product-origin">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nước sản xuất</label>
                                <input type="text" class="form-control" id="product-country">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dung tích</label>
                                <input type="text" class="form-control" id="product-volume">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Loại da</label>
                                <input type="text" class="form-control" id="product-skin-type">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mùi hương</label>
                                <input type="text" class="form-control" id="product-scent">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="saveProduct()">Lưu</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Product Modal -->
    <div class="modal fade" id="viewProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết Sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="product-details"></div>
            </div>
        </div>
    </div>

    <!-- Upload Images Modal -->
    <div class="modal fade" id="uploadImagesModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật Hình ảnh</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="upload-product-id">
                    <div class="mb-3">
                        <label class="form-label">Chọn ảnh mới <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="upload-images" accept="image/*" multiple required>
                        <small class="text-muted">Chọn nhiều ảnh để thay thế toàn bộ ảnh cũ</small>
                    </div>
                    <div id="current-images" class="image-gallery"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="uploadImages()">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = 'http://localhost:8000/admin';
        let modal = null;

        function showAlert(msg, type = 'success') {
            const html = `<div class="alert alert-${type} alert-dismissible fade show">
                ${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
            document.getElementById('alert-container').innerHTML = html;
            setTimeout(() => document.getElementById('alert-container').innerHTML = '', 3000);
        }

        function formatMoney(amount) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
        }

        function getStockBadge(stock) {
            if (stock === 0) return '<span class="stock-badge out-of-stock">Hết hàng</span>';
            if (stock < 10) return `<span class="stock-badge low-stock">${stock}</span>`;
            return `<span class="stock-badge in-stock">${stock}</span>`;
        }

        function getImageUrl(images) {
            if (!images || images.length === 0) return 'https://via.placeholder.com/60?text=No+Image';
            const firstImage = images[0];
            const url = typeof firstImage === 'object' ? firstImage.url : firstImage;
            return `http://localhost:8000/storage/${url}`;
        }

        async function loadFilters() {
            try {
                const [brandsRes, categoriesRes] = await Promise.all([
                    fetch(`${API_URL}/brands`),
                    fetch(`${API_URL}/categories`)
                ]);

                const brandsData = await brandsRes.json();
                const categoriesData = await categoriesRes.json();

                const brands = brandsData.data?.data || brandsData.data || [];
                const categories = categoriesData.data || [];

                const productBrand = document.getElementById('product-brand');
                brands.forEach(brand => {
                    productBrand.innerHTML += `<option value="${brand.id}">${brand.brand_name}</option>`;
                });

                const productCategory = document.getElementById('product-category');
                categories.forEach(category => {
                    productCategory.innerHTML += `<option value="${category.id}">${category.name}</option>`;
                });

            } catch (error) {
                showAlert('Lỗi khi tải danh mục: ' + error.message, 'danger');
            }
        }

        async function loadProducts() {
            try {
                document.getElementById('loading').classList.remove('d-none');
                document.getElementById('products-table').classList.add('d-none');

                const search = document.getElementById('search-input').value.trim();
                let url = `${API_URL}/products`;
                if (search) url += `?search=${encodeURIComponent(search)}`;

                const response = await fetch(url);
                const result = await response.json();
                const products = result.data?.data || result.data || [];

                const tbody = document.getElementById('products-tbody');
                tbody.innerHTML = '';

                if (products.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center">Không có dữ liệu</td></tr>';
                } else {
                    products.forEach(p => {
                        tbody.innerHTML += `
                            <tr>
                                <td><img src="${getImageUrl(p.images)}" class="product-img" 
                                    onerror="this.src='https://via.placeholder.com/60?text=No+Image'"></td>
                                <td><strong>${p.name}</strong></td>
                                <td>${p.brand?.brand_name || 'N/A'}</td>
                                <td>${p.category?.name || 'N/A'}</td>
                                <td><strong class="text-danger">${formatMoney(p.price)}</strong></td>
                                <td>${getStockBadge(p.stock)}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="viewProduct(${p.id})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="showEditModal(${p.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary" onclick="showUploadImagesModal(${p.id})">
                                        <i class="fas fa-image"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteProduct(${p.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                    });
                }

                document.getElementById('loading').classList.add('d-none');
                document.getElementById('products-table').classList.remove('d-none');

            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
                document.getElementById('loading').classList.add('d-none');
            }
        }

        async function viewProduct(id) {
            try {
                const response = await fetch(`${API_URL}/products/${id}`);
                const result = await response.json();
                const p = result.data;

                let imagesHtml = '';
                if (p.images && p.images.length > 0) {
                    imagesHtml = '<h6 class="mt-3">Hình ảnh sản phẩm:</h6><div class="image-gallery">';
                    p.images.forEach(img => {
                        imagesHtml += `<img src="${getImageUrl([img])}" onerror="this.src='https://via.placeholder.com/80?text=No+Image'">`;
                    });
                    imagesHtml += '</div>';
                }

                document.getElementById('product-details').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tên:</strong> ${p.name}</p>
                            <p><strong>Giá:</strong> <span class="text-danger">${formatMoney(p.price)}</span></p>
                            <p><strong>Tồn kho:</strong> ${getStockBadge(p.stock)}</p>
                            <p><strong>Thương hiệu:</strong> ${p.brand?.brand_name || 'N/A'}</p>
                            <p><strong>Loại:</strong> ${p.category?.name || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Mã vạch:</strong> ${p.barcode || 'N/A'}</p>
                            <p><strong>Xuất xứ:</strong> ${p.origin || 'N/A'}</p>
                            <p><strong>Nước sản xuất:</strong> ${p.manufacture_country || 'N/A'}</p>
                            <p><strong>Dung tích:</strong> ${p.volume || 'N/A'}</p>
                            <p><strong>Loại da:</strong> ${p.skin_type || 'N/A'}</p>
                            <p><strong>Mùi hương:</strong> ${p.scent || 'N/A'}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <p><strong>Mô tả:</strong> ${p.description || 'N/A'}</p>
                    </div>
                    ${imagesHtml}`;

                new bootstrap.Modal(document.getElementById('viewProductModal')).show();
            } catch (error) {
                showAlert('Lỗi khi xem chi tiết: ' + error.message, 'danger');
            }
        }

        function showAddModal() {
            document.getElementById('modal-title').textContent = 'Thêm Sản phẩm';
            document.getElementById('product-form').reset();
            document.getElementById('product-id').value = '';
            modal = new bootstrap.Modal(document.getElementById('productModal'));
            modal.show();
        }

        async function showEditModal(id) {
            try {
                const response = await fetch(`${API_URL}/products/${id}`);
                const p = (await response.json()).data;

                document.getElementById('modal-title').textContent = 'Sửa Sản phẩm';
                document.getElementById('product-id').value = p.id;
                document.getElementById('product-name').value = p.name;
                document.getElementById('product-price').value = p.price;
                document.getElementById('product-stock').value = p.stock;
                document.getElementById('product-brand').value = p.brand_id;
                document.getElementById('product-category').value = p.subcategory_id;
                document.getElementById('product-description').value = p.description || '';
                document.getElementById('product-barcode').value = p.barcode || '';
                document.getElementById('product-origin').value = p.origin || '';
                document.getElementById('product-country').value = p.manufacture_country || '';
                document.getElementById('product-volume').value = p.volume || '';
                document.getElementById('product-skin-type').value = p.skin_type || '';
                document.getElementById('product-scent').value = p.scent || '';

                modal = new bootstrap.Modal(document.getElementById('productModal'));
                modal.show();

            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        async function saveProduct() {
            try {
                const id = document.getElementById('product-id').value;

                const data = {
                    'name': document.getElementById('product-name').value.trim(),
                    'price': document.getElementById('product-price').value.trim(),
                    'stock': document.getElementById('product-stock').value.trim(),
                    'brand_id': document.getElementById('product-brand').value.trim(),
                    'subcategory_id': document.getElementById('product-category').value.trim(),
                    'description': document.getElementById('product-description').value.trim(),
                    'barcode': document.getElementById('product-barcode').value.trim(),
                    'origin': document.getElementById('product-origin').value.trim(),
                    'manufacture_country': document.getElementById('product-country').value.trim(),
                    'volume': document.getElementById('product-volume').value.trim(),
                    'skin_type': document.getElementById('product-skin-type').value.trim(),
                    'scent': document.getElementById('product-scent').value.trim()
                };

                let url = `${API_URL}/products`;
                let method = 'POST';

                if (id) {
                    url = `${url}/${id}`;
                    method = 'PUT';
                }

                const response = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                if (!response.ok) throw new Error(id ? 'Không thể cập nhật' : 'Không thể thêm mới');

                modal.hide();
                showAlert(id ? 'Cập nhật thành công!' : 'Thêm mới thành công!');
                loadProducts();

            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        async function showUploadImagesModal(id) {
            try {
                const response = await fetch(`${API_URL}/products/${id}`);
                const p = (await response.json()).data;

                document.getElementById('upload-product-id').value = id;
                
                const currentImagesDiv = document.getElementById('current-images');
                currentImagesDiv.innerHTML = '<h6>Ảnh hiện tại:</h6>';
                
                if (p.images && p.images.length > 0) {
                    p.images.forEach(img => {
                        currentImagesDiv.innerHTML += `<img src="${getImageUrl([img])}" onerror="this.src='https://via.placeholder.com/80?text=No+Image'">`;
                    });
                } else {
                    currentImagesDiv.innerHTML += '<p class="text-muted">Chưa có ảnh</p>';
                }

                new bootstrap.Modal(document.getElementById('uploadImagesModal')).show();

            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        async function uploadImages() {
            try {
                const id = document.getElementById('upload-product-id').value;
                const images = document.getElementById('upload-images').files;

                if (images.length === 0) {
                    showAlert('Vui lòng chọn ít nhất 1 ảnh', 'warning');
                    return;
                }

                const formData = new FormData();
                Array.from(images).forEach(img => formData.append('images[]', img));

                const response = await fetch(`${API_URL}/products/${id}/images`, {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) throw new Error('Không thể cập nhật ảnh');

                bootstrap.Modal.getInstance(document.getElementById('uploadImagesModal')).hide();
                showAlert('Cập nhật ảnh thành công!');
                loadProducts();

            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        async function deleteProduct(id) {
            if (!confirm('Bạn có chắc muốn xóa?')) return;

            try {
                const response = await fetch(`${API_URL}/products/${id}`, { method: 'DELETE' });
                if (!response.ok) throw new Error('Không thể xóa');

                showAlert('Xóa thành công!');
                loadProducts();

            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            loadFilters();
            loadProducts();
        });

        document.getElementById('search-input').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') loadProducts();
        });
    </script>
</body>
</html>
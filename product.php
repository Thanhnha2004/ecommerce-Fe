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
        .in-stock { background-color: #d1fae5; color: #065f46; }
        .out-of-stock { background-color: #fee2e2; color: #991b1b; }
        .low-stock { background-color: #fef3c7; color: #92400e; }
        .image-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 2px solid #e5e7eb;
            border-radius: 4px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">Admin Panel</div>
        <nav>
            <a href="dashboard.html"><i class="fas fa-chart-line me-2"></i> Dashboard</a>
            <a href="products.html" class="active"><i class="fas fa-box me-2"></i> Quản lý sản phẩm</a>
            <a href="brands.html"><i class="fas fa-tag me-2"></i> Quản lý thương hiệu</a>
            <a href="orders.html"><i class="fas fa-shopping-cart me-2"></i> Quản lý đơn hàng</a>
            <a href="users.html"><i class="fas fa-users me-2"></i> Quản lý tài khoản</a>
            <a href="categories.html"><i class="fas fa-list me-2"></i> Quản lý loại sản phẩm</a>
            <a href="#"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <p class="text-muted mb-0">Admin / Quản lý sản phẩm</p>
            <h2 class="mb-0">Quản lý Sản phẩm</h2>
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- Alert -->
            <div id="alert-container"></div>

            <!-- Filter & Search -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" id="search-input" class="form-control" placeholder="Tìm sản phẩm...">
                        </div>
                        <div class="col-md-2">
                            <select id="brand-filter" class="form-select">
                                <option value="">Tất cả thương hiệu</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="category-filter" class="form-select">
                                <option value="">Tất cả loại</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" onclick="loadProducts()">
                                <i class="fas fa-search me-2"></i>Tìm
                            </button>
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-success w-100" onclick="openAddModal()">
                                <i class="fas fa-plus me-2"></i>Thêm
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Danh sách Sản phẩm</h5>
                </div>
                <div class="card-body">
                    <div id="loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="products-table" class="d-none">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="80">Ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Thương hiệu</th>
                                        <th>Loại</th>
                                        <th>Giá</th>
                                        <th>Tồn kho</th>
                                        <th width="150">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="products-tbody"></tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div id="pagination" class="d-flex justify-content-center mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Thêm Sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="product-form" enctype="multipart/form-data">
                        <input type="hidden" id="product-id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product-name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="product-name" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="product-price" class="form-label">Giá <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="product-price" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="product-stock" class="form-label">Tồn kho <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="product-stock" min="0" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product-brand" class="form-label">Thương hiệu <span class="text-danger">*</span></label>
                                    <select class="form-select" id="product-brand" required>
                                        <option value="">Chọn thương hiệu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product-category" class="form-label">Loại sản phẩm <span class="text-danger">*</span></label>
                                    <select class="form-select" id="product-category" required>
                                        <option value="">Chọn loại sản phẩm</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="product-description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="product-description" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product-barcode" class="form-label">Mã vạch</label>
                                    <input type="text" class="form-control" id="product-barcode">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product-origin" class="form-label">Xuất xứ</label>
                                    <input type="text" class="form-control" id="product-origin">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product-country" class="form-label">Nước sản xuất</label>
                                    <input type="text" class="form-control" id="product-country">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product-volume" class="form-label">Dung tích</label>
                                    <input type="text" class="form-control" id="product-volume">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product-skin-type" class="form-label">Loại da</label>
                                    <input type="text" class="form-control" id="product-skin-type">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="product-scent" class="form-label">Mùi hương</label>
                                    <input type="text" class="form-control" id="product-scent">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="product-images" class="form-label">Hình ảnh</label>
                            <input type="file" class="form-control" id="product-images" accept="image/*" multiple>
                            <small class="text-muted">Có thể chọn nhiều ảnh</small>
                            <div id="image-preview" class="mt-2"></div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_BASE_URL = 'http://localhost:8000/admin';
        let currentProductModal = null;
        let brands = [];
        let categories = [];

        // Show alert
        function showAlert(message, type = 'success') {
            const alert = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
            document.getElementById('alert-container').innerHTML = alert;
            setTimeout(() => {
                document.getElementById('alert-container').innerHTML = '';
            }, 3000);
        }

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(amount);
        }

        // Get stock badge
        function getStockBadge(stock) {
            if (stock === 0) {
                return '<span class="stock-badge out-of-stock">Hết hàng</span>';
            } else if (stock < 10) {
                return `<span class="stock-badge low-stock">${stock}</span>`;
            }
            return `<span class="stock-badge in-stock">${stock}</span>`;
        }

        // Load brands and categories
        async function loadBrandsAndCategories() {
            try {
                const [brandsRes, categoriesRes] = await Promise.all([
                    fetch(`${API_BASE_URL}/brands`),
                    fetch(`${API_BASE_URL}/categories`)
                ]);

                const brandsData = await brandsRes.json();
                const categoriesData = await categoriesRes.json();

                brands = brandsData.data || [];
                categories = categoriesData.data || [];

                // Populate filters
                const brandFilter = document.getElementById('brand-filter');
                const categoryFilter = document.getElementById('category-filter');
                const productBrand = document.getElementById('product-brand');
                const productCategory = document.getElementById('product-category');

                brands.forEach(brand => {
                    brandFilter.innerHTML += `<option value="${brand.id}">${brand.brand_name}</option>`;
                    productBrand.innerHTML += `<option value="${brand.id}">${brand.brand_name}</option>`;
                });

                categories.forEach(category => {
                    categoryFilter.innerHTML += `<option value="${category.id}">${category.name}</option>`;
                    productCategory.innerHTML += `<option value="${category.id}">${category.name}</option>`;
                });

            } catch (error) {
                console.error('Error loading filters:', error);
            }
        }

        // Load products
        async function loadProducts() {
            try {
                document.getElementById('loading').classList.remove('d-none');
                document.getElementById('products-table').classList.add('d-none');

                const response = await fetch(`${API_BASE_URL}/products`);
                if (!response.ok) throw new Error('Không thể tải dữ liệu');

                const result = await response.json();
                const products = result.data?.data || result.data || [];

                const tbody = document.getElementById('products-tbody');
                tbody.innerHTML = '';

                if (products.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center">Không có sản phẩm nào</td></tr>';
                } else {
                    products.forEach(product => {
                        const imageUrl = product.images && product.images[0]
                            ? `http://localhost:8000/storage/${product.images[0].url}`
                            : 'https://via.placeholder.com/60?text=No+Image';

                        const row = `
                            <tr>
                                <td>
                                    <img src="${imageUrl}" alt="${product.name}" class="product-img" onerror="this.src='https://via.placeholder.com/60?text=No+Image'">
                                </td>
                                <td><strong>${product.name}</strong></td>
                                <td>${product.brand?.brand_name || 'N/A'}</td>
                                <td>${product.category?.name || 'N/A'}</td>
                                <td><strong class="text-danger">${formatCurrency(product.price)}</strong></td>
                                <td>${getStockBadge(product.stock)}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="viewProduct(${product.id})" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editProduct(${product.id})" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteProduct(${product.id})" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                        tbody.innerHTML += row;
                    });
                }

                document.getElementById('loading').classList.add('d-none');
                document.getElementById('products-table').classList.remove('d-none');

            } catch (error) {
                console.error('Error:', error);
                showAlert('Lỗi khi tải dữ liệu: ' + error.message, 'danger');
                document.getElementById('loading').classList.add('d-none');
            }
        }

        // Open add modal
        function openAddModal() {
            document.getElementById('modal-title').textContent = 'Thêm Sản phẩm';
            document.getElementById('product-form').reset();
            document.getElementById('product-id').value = '';
            document.getElementById('image-preview').innerHTML = '';
            currentProductModal = new bootstrap.Modal(document.getElementById('productModal'));
            currentProductModal.show();
        }

        // Preview images
        document.getElementById('product-images').addEventListener('change', (e) => {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';
            const files = e.target.files;
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.innerHTML += `<img src="${e.target.result}" class="image-preview">`;
                };
                reader.readAsDataURL(file);
            }
        });

        // View product
        async function viewProduct(id) {
            try {
                const response = await fetch(`${API_BASE_URL}/products/${id}`);
                const result = await response.json();
                const product = result.data;

                let imagesHtml = '';
                if (product.images && product.images.length > 0) {
                    imagesHtml = '<div class="mb-3"><strong>Hình ảnh:</strong><div class="mt-2">';
                    product.images.forEach(img => {
                        imagesHtml += `<img src="http://localhost:8000/storage/${img.url}" class="image-preview" onerror="this.src='https://via.placeholder.com/100?text=No+Image'">`;
                    });
                    imagesHtml += '</div></div>';
                }

                document.getElementById('product-details').innerHTML = `
                    ${imagesHtml}
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tên sản phẩm:</strong> ${product.name}</p>
                            <p><strong>Thương hiệu:</strong> ${product.brand?.brand_name || 'N/A'}</p>
                            <p><strong>Loại:</strong> ${product.category?.name || 'N/A'}</p>
                            <p><strong>Giá:</strong> <span class="text-danger fw-bold">${formatCurrency(product.price)}</span></p>
                            <p><strong>Tồn kho:</strong> ${getStockBadge(product.stock)}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Mã vạch:</strong> ${product.barcode || 'N/A'}</p>
                            <p><strong>Xuất xứ:</strong> ${product.origin || 'N/A'}</p>
                            <p><strong>Nước sản xuất:</strong> ${product.manufacture_country || 'N/A'}</p>
                            <p><strong>Dung tích:</strong> ${product.volume || 'N/A'}</p>
                            <p><strong>Loại da:</strong> ${product.skin_type || 'N/A'}</p>
                            <p><strong>Mùi hương:</strong> ${product.scent || 'N/A'}</p>
                        </div>
                    </div>
                    <div><strong>Mô tả:</strong><p>${product.description || 'Chưa có mô tả'}</p></div>
                `;

                new bootstrap.Modal(document.getElementById('viewProductModal')).show();
            } catch (error) {
                showAlert('Lỗi khi xem chi tiết: ' + error.message, 'danger');
            }
        }

        // Edit product
        async function editProduct(id) {
            try {
                const response = await fetch(`${API_BASE_URL}/products/${id}`);
                const result = await response.json();
                const product = result.data;

                document.getElementById('modal-title').textContent = 'Sửa Sản phẩm';
                document.getElementById('product-id').value = product.id;
                document.getElementById('product-name').value = product.name;
                document.getElementById('product-price').value = product.price;
                document.getElementById('product-stock').value = product.stock;
                document.getElementById('product-brand').value = product.brand_id;
                document.getElementById('product-category').value = product.subcategory_id;
                document.getElementById('product-description').value = product.description || '';
                document.getElementById('product-barcode').value = product.barcode || '';
                document.getElementById('product-origin').value = product.origin || '';
                document.getElementById('product-country').value = product.manufacture_country || '';
                document.getElementById('product-volume').value = product.volume || '';
                document.getElementById('product-skin-type').value = product.skin_type || '';
                document.getElementById('product-scent').value = product.scent || '';

                // Show existing images
                const preview = document.getElementById('image-preview');
                preview.innerHTML = '';
                if (product.images && product.images.length > 0) {
                    product.images.forEach(img => {
                        preview.innerHTML += `<img src="http://localhost:8000/storage/${img.url}" class="image-preview">`;
                    });
                }

                currentProductModal = new bootstrap.Modal(document.getElementById('productModal'));
                currentProductModal.show();
            } catch (error) {
                showAlert('Lỗi khi tải thông tin: ' + error.message, 'danger');
            }
        }

        // Save product
        async function saveProduct() {
            try {
                const id = document.getElementById('product-id').value;
                const formData = new FormData();

                // Add basic fields
                formData.append('name', document.getElementById('product-name').value.trim());
                formData.append('price', document.getElementById('product-price').value);
                formData.append('stock', document.getElementById('product-stock').value);
                formData.append('brand_id', document.getElementById('product-brand').value);
                formData.append('subcategory_id', document.getElementById('product-category').value);
                formData.append('description', document.getElementById('product-description').value.trim());
                formData.append('barcode', document.getElementById('product-barcode').value.trim());
                formData.append('origin', document.getElementById('product-origin').value.trim());
                formData.append('manufacture_country', document.getElementById('product-country').value.trim());
                formData.append('volume', document.getElementById('product-volume').value.trim());
                formData.append('skin_type', document.getElementById('product-skin-type').value.trim());
                formData.append('scent', document.getElementById('product-scent').value.trim());

                // Add images (only for new product)
                if (!id) {
                    const images = document.getElementById('product-images').files;
                    for (let i = 0; i < images.length; i++) {
                        formData.append('images[]', images[i]);
                    }
                }

                const url = id ? `${API_BASE_URL}/products/${id}` : `${API_BASE_URL}/products`;
                const method = id ? 'PUT' : 'POST';

                let response;
                if (id) {
                    // For PUT, convert FormData to JSON
                    const data = {};
                    formData.forEach((value, key) => {
                        data[key] = value;
                    });
                    response = await fetch(url, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });
                } else {
                    // For POST with images
                    response = await fetch(url, {
                        method: 'POST',
                        body: formData
                    });
                }

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Lỗi khi lưu dữ liệu');
                }

                currentProductModal.hide();
                showAlert(id ? 'Cập nhật sản phẩm thành công!' : 'Thêm sản phẩm thành công!');
                loadProducts();
            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        // Delete product
        async function deleteProduct(id) {
            if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;

            try {
                const response = await fetch(`${API_BASE_URL}/products/${id}`, {
                    method: 'DELETE'
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.error || 'Xóa thất bại');
                }

                showAlert('Xóa sản phẩm thành công!');
                loadProducts();
            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        // Load on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadBrandsAndCategories();
            loadProducts();
        });

        // Search on Enter
        document.getElementById('search-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') loadProducts();
        });
    </script>
</body>
</html>
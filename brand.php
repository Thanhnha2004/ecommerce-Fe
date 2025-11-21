<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Thương hiệu</title>
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
        .brand-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 5px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">Admin Panel</div>
        <nav>
            <a href="dashboard.html"><i class="fas fa-chart-line me-2"></i> Dashboard</a>
            <a href="products.html"><i class="fas fa-box me-2"></i> Quản lý sản phẩm</a>
            <a href="brands.html" class="active"><i class="fas fa-tag me-2"></i> Quản lý thương hiệu</a>
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
            <p class="text-muted mb-0">Admin / Quản lý thương hiệu</p>
            <h2 class="mb-0">Quản lý Thương hiệu</h2>
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- Alert -->
            <div id="alert-container"></div>

            <!-- Action Bar -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <input type="text" id="search-input" class="form-control" placeholder="Tìm kiếm thương hiệu...">
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary" onclick="openAddModal()">
                                <i class="fas fa-plus me-2"></i>Thêm Thương hiệu
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Brands Table -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Danh sách Thương hiệu</h5>
                </div>
                <div class="card-body">
                    <div id="loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="brands-table" class="d-none">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="80">ID</th>
                                        <th width="100">Logo</th>
                                        <th>Tên thương hiệu</th>
                                        <th width="150">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="brands-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Brand Modal -->
    <div class="modal fade" id="brandModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Thêm Thương hiệu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="brand-form">
                        <input type="hidden" id="brand-id">
                        <div class="mb-3">
                            <label for="brand-name" class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="brand-name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="saveBrand()">Lưu</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_BASE_URL = 'http://localhost:8000/admin';
        let currentBrandModal = null;

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

        // Load brands
        async function loadBrands() {
            try {
                document.getElementById('loading').classList.remove('d-none');
                document.getElementById('brands-table').classList.add('d-none');

                const response = await fetch(`${API_BASE_URL}/brands`);
                if (!response.ok) throw new Error('Không thể tải dữ liệu');

                const result = await response.json();
                const brands = result.data || [];

                const tbody = document.getElementById('brands-tbody');
                tbody.innerHTML = '';

                if (brands.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center">Không có thương hiệu nào</td></tr>';
                } else {
                    brands.forEach(brand => {
                        const logoUrl = brand.logo?.url 
                            ? `http://localhost:8000/storage/${brand.logo.url}` 
                            : 'https://via.placeholder.com/60?text=No+Logo';

                        const row = `
                            <tr>
                                <td>${brand.id}</td>
                                <td>
                                    <img src="${logoUrl}" alt="${brand.brand_name}" class="brand-logo" onerror="this.src='https://via.placeholder.com/60?text=No+Logo'">
                                </td>
                                <td><strong>${brand.brand_name}</strong></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="editBrand(${brand.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteBrand(${brand.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                        tbody.innerHTML += row;
                    });
                }

                document.getElementById('loading').classList.add('d-none');
                document.getElementById('brands-table').classList.remove('d-none');

            } catch (error) {
                console.error('Error:', error);
                showAlert('Lỗi khi tải dữ liệu: ' + error.message, 'danger');
                document.getElementById('loading').classList.add('d-none');
            }
        }

        // Open add modal
        function openAddModal() {
            document.getElementById('modal-title').textContent = 'Thêm Thương hiệu';
            document.getElementById('brand-form').reset();
            document.getElementById('brand-id').value = '';
            currentBrandModal = new bootstrap.Modal(document.getElementById('brandModal'));
            currentBrandModal.show();
        }

        // Edit brand
        async function editBrand(id) {
            try {
                const response = await fetch(`${API_BASE_URL}/brands/${id}`);
                const result = await response.json();
                const brand = result.data;

                document.getElementById('modal-title').textContent = 'Sửa Thương hiệu';
                document.getElementById('brand-id').value = brand.id;
                document.getElementById('brand-name').value = brand.brand_name;

                currentBrandModal = new bootstrap.Modal(document.getElementById('brandModal'));
                currentBrandModal.show();
            } catch (error) {
                showAlert('Lỗi khi tải thông tin: ' + error.message, 'danger');
            }
        }

        // Save brand
        async function saveBrand() {
            try {
                const id = document.getElementById('brand-id').value;
                const brandName = document.getElementById('brand-name').value.trim();

                if (!brandName) {
                    showAlert('Vui lòng nhập tên thương hiệu', 'warning');
                    return;
                }

                const data = { brand_name: brandName };
                const url = id ? `${API_BASE_URL}/brands/${id}` : `${API_BASE_URL}/brands`;
                const method = id ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Lỗi khi lưu dữ liệu');
                }

                currentBrandModal.hide();
                showAlert(id ? 'Cập nhật thương hiệu thành công!' : 'Thêm thương hiệu thành công!');
                loadBrands();
            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        // Delete brand
        async function deleteBrand(id) {
            if (!confirm('Bạn có chắc muốn xóa thương hiệu này?')) return;

            try {
                const response = await fetch(`${API_BASE_URL}/brands/${id}`, {
                    method: 'DELETE'
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.error || 'Xóa thất bại');
                }

                showAlert('Xóa thương hiệu thành công!');
                loadBrands();
            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        // Search functionality
        let searchTimeout;
        document.getElementById('search-input').addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('#brands-tbody tr');
                
                rows.forEach(row => {
                    const brandName = row.cells[2]?.textContent.toLowerCase() || '';
                    if (brandName.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }, 300);
        });

        // Load on page load
        document.addEventListener('DOMContentLoaded', loadBrands);
    </script>
</body>
</html>
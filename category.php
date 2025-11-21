<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Loại sản phẩm</title>
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
        .description-cell {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            <a href="brands.html"><i class="fas fa-tag me-2"></i> Quản lý thương hiệu</a>
            <a href="orders.html"><i class="fas fa-shopping-cart me-2"></i> Quản lý đơn hàng</a>
            <a href="users.html"><i class="fas fa-users me-2"></i> Quản lý tài khoản</a>
            <a href="categories.html" class="active"><i class="fas fa-list me-2"></i> Quản lý loại sản phẩm</a>
            <a href="#"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <p class="text-muted mb-0">Admin / Quản lý loại sản phẩm</p>
            <h2 class="mb-0">Quản lý Loại sản phẩm</h2>
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
                            <input type="text" id="search-input" class="form-control" placeholder="Tìm kiếm loại sản phẩm...">
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary" onclick="openAddModal()">
                                <i class="fas fa-plus me-2"></i>Thêm Loại sản phẩm
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories Table -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Danh sách Loại sản phẩm</h5>
                </div>
                <div class="card-body">
                    <div id="loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="categories-table" class="d-none">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="80">ID</th>
                                        <th>Tên loại sản phẩm</th>
                                        <th>Mô tả</th>
                                        <th width="150">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="categories-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Thêm Loại sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="category-form">
                        <input type="hidden" id="category-id">
                        <div class="mb-3">
                            <label for="category-name" class="form-label">Tên loại sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="category-name" required>
                        </div>
                        <div class="mb-3">
                            <label for="category-description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="category-description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="saveCategory()">Lưu</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Category Modal -->
    <div class="modal fade" id="viewCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết Loại sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="category-details"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_BASE_URL = 'http://localhost:8000/admin';
        let currentCategoryModal = null;

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

        // Load categories
        async function loadCategories() {
            try {
                document.getElementById('loading').classList.remove('d-none');
                document.getElementById('categories-table').classList.add('d-none');

                const response = await fetch(`${API_BASE_URL}/categories`);
                if (!response.ok) throw new Error('Không thể tải dữ liệu');

                const result = await response.json();
                const categories = result.data || [];

                const tbody = document.getElementById('categories-tbody');
                tbody.innerHTML = '';

                if (categories.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center">Không có loại sản phẩm nào</td></tr>';
                } else {
                    categories.forEach(category => {
                        const description = category.description 
                            ? `<span class="description-cell" title="${category.description}">${category.description}</span>`
                            : '<span class="text-muted">Chưa có mô tả</span>';

                        const row = `
                            <tr>
                                <td>${category.id}</td>
                                <td><strong>${category.name}</strong></td>
                                <td>${description}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="viewCategory(${category.id})" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editCategory(${category.id})" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteCategory(${category.id})" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                        tbody.innerHTML += row;
                    });
                }

                document.getElementById('loading').classList.add('d-none');
                document.getElementById('categories-table').classList.remove('d-none');

            } catch (error) {
                console.error('Error:', error);
                showAlert('Lỗi khi tải dữ liệu: ' + error.message, 'danger');
                document.getElementById('loading').classList.add('d-none');
            }
        }

        // Open add modal
        function openAddModal() {
            document.getElementById('modal-title').textContent = 'Thêm Loại sản phẩm';
            document.getElementById('category-form').reset();
            document.getElementById('category-id').value = '';
            currentCategoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
            currentCategoryModal.show();
        }

        // View category details
        async function viewCategory(id) {
            try {
                const response = await fetch(`${API_BASE_URL}/categories/${id}`);
                const result = await response.json();
                const category = result.data;

                let productsHtml = '';
                if (category.products && category.products.length > 0) {
                    productsHtml = `
                        <h6 class="mt-4">Sản phẩm thuộc loại này (${category.products.length}):</h6>
                        <div class="list-group">
                    `;
                    category.products.slice(0, 10).forEach(product => {
                        productsHtml += `
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span>${product.name}</span>
                                    <span class="badge bg-primary">${product.price?.toLocaleString('vi-VN')} đ</span>
                                </div>
                            </div>
                        `;
                    });
                    productsHtml += '</div>';
                    if (category.products.length > 10) {
                        productsHtml += `<p class="text-muted mt-2"><small>... và ${category.products.length - 10} sản phẩm khác</small></p>`;
                    }
                } else {
                    productsHtml = '<p class="text-muted mt-3">Chưa có sản phẩm nào trong loại này</p>';
                }

                document.getElementById('category-details').innerHTML = `
                    <div class="row">
                        <div class="col-12">
                            <p><strong>ID:</strong> ${category.id}</p>
                            <p><strong>Tên loại sản phẩm:</strong> ${category.name}</p>
                            <p><strong>Mô tả:</strong> ${category.description || '<span class="text-muted">Chưa có mô tả</span>'}</p>
                        </div>
                    </div>
                    ${productsHtml}
                `;

                new bootstrap.Modal(document.getElementById('viewCategoryModal')).show();
            } catch (error) {
                showAlert('Lỗi khi xem chi tiết: ' + error.message, 'danger');
            }
        }

        // Edit category
        async function editCategory(id) {
            try {
                const response = await fetch(`${API_BASE_URL}/categories/${id}`);
                const result = await response.json();
                const category = result.data;

                document.getElementById('modal-title').textContent = 'Sửa Loại sản phẩm';
                document.getElementById('category-id').value = category.id;
                document.getElementById('category-name').value = category.name;
                document.getElementById('category-description').value = category.description || '';

                currentCategoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
                currentCategoryModal.show();
            } catch (error) {
                showAlert('Lỗi khi tải thông tin: ' + error.message, 'danger');
            }
        }

        // Save category
        async function saveCategory() {
            try {
                const id = document.getElementById('category-id').value;
                const name = document.getElementById('category-name').value.trim();
                const description = document.getElementById('category-description').value.trim();

                if (!name) {
                    showAlert('Vui lòng nhập tên loại sản phẩm', 'warning');
                    return;
                }

                const data = { 
                    name: name,
                    description: description || null
                };
                
                const url = id ? `${API_BASE_URL}/categories/${id}` : `${API_BASE_URL}/categories`;
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

                currentCategoryModal.hide();
                showAlert(id ? 'Cập nhật loại sản phẩm thành công!' : 'Thêm loại sản phẩm thành công!');
                loadCategories();
            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        // Delete category
        async function deleteCategory(id) {
            if (!confirm('Bạn có chắc muốn xóa loại sản phẩm này?\n\nLưu ý: Không thể xóa nếu có sản phẩm đang thuộc loại này.')) return;

            try {
                const response = await fetch(`${API_BASE_URL}/categories/${id}`, {
                    method: 'DELETE'
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.error || 'Xóa thất bại');
                }

                showAlert('Xóa loại sản phẩm thành công!');
                loadCategories();
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
                const rows = document.querySelectorAll('#categories-tbody tr');
                
                rows.forEach(row => {
                    const categoryName = row.cells[1]?.textContent.toLowerCase() || '';
                    const description = row.cells[2]?.textContent.toLowerCase() || '';
                    if (categoryName.includes(searchTerm) || description.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }, 300);
        });

        // Load on page load
        document.addEventListener('DOMContentLoaded', loadCategories);
    </script>
</body>
</html>
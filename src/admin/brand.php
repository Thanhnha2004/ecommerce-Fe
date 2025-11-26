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
            border-radius: 4px;
            border: 1px solid #e5e7eb;
            background-color: white;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php include '../includes/sidebar.php'; ?>

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

            <!-- Search & Add -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <input type="text" id="search-input" class="form-control" placeholder="Tìm thương hiệu...">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" onclick="searchBrands()">
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

            <!-- Brands Table -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Danh sách Thương hiệu</h5>
                </div>
                <div class="card-body">
                    <!-- Loading -->
                    <div id="loading" class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                    </div>

                    <!-- Table -->
                    <div id="brands-table" class="d-none">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="80">Logo</th>
                                    <th>Tên thương hiệu</th>
                                    <th>Mô tả</th>
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

    <!-- Modal Add/Edit -->
    <div class="modal fade" id="brandModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modal-title">Thêm Thương hiệu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="brand-form">
                        <input type="hidden" id="brand-id">
                        <div class="mb-3">
                            <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="brand-name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" id="brand-description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Logo thương hiệu</label>
                            <input type="file" class="form-control" id="brand-logo" accept="image/*">
                            <small class="text-muted">Chọn file ảnh (JPG, PNG, GIF)</small>
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
        const API_URL = 'http://localhost:8000/admin/brands';
        let modal = null;
        let currentPage = 1;

        function showAlert(message, type = 'success') {
            const html = `
                <div class="alert alert-${type} alert-dismissible fade show">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
            document.getElementById('alert-container').innerHTML = html;
            setTimeout(() => {
                document.getElementById('alert-container').innerHTML = '';
            }, 3000);
        }

        function getLogoUrl(logo) {
            if (!logo) return 'https://via.placeholder.com/60?text=No+Logo';
            return `http://localhost:8000/storage/${logo.url}`;
        }

        function searchBrands() {
            currentPage = 1;
            loadBrands();
        }

        async function loadBrands(page = 1) {
            try {
                document.getElementById('loading').classList.remove('d-none');
                document.getElementById('brands-table').classList.add('d-none');

                const search = document.getElementById('search-input').value.trim();
                let url = `${API_URL}?page=${page}`;
                if (search) url += `&search=${encodeURIComponent(search)}`;

                const response = await fetch(url);
                const result = await response.json();
                const brands = result.data?.data || result.data || [];
                const pagination = result.data;

                const tbody = document.getElementById('brands-tbody');
                tbody.innerHTML = '';

                if (brands.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center">Không có dữ liệu</td></tr>';
                } else {
                    brands.forEach(brand => {
                        tbody.innerHTML += `
                            <tr>
                                <td>
                                    <img src="${getLogoUrl(brand.logo)}" 
                                         class="brand-logo" 
                                         onerror="this.src='https://via.placeholder.com/60?text=No+Logo'">
                                </td>
                                <td><strong>${brand.brand_name}</strong></td>
                                <td>${brand.description || 'Chưa có mô tả'}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="showEditModal(${brand.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteBrand(${brand.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                    });
                }

                renderPagination(pagination);

                document.getElementById('loading').classList.add('d-none');
                document.getElementById('brands-table').classList.remove('d-none');

            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
                document.getElementById('loading').classList.add('d-none');
            }
        }

        function renderPagination(data) {
            const paginationDiv = document.createElement('div');
            paginationDiv.className = 'd-flex justify-content-center mt-3';
            paginationDiv.innerHTML = '';

            if (data && data.last_page > 1) {
                let html = '<nav><ul class="pagination">';

                if (data.current_page > 1) {
                    html += `<li class="page-item"><a class="page-link" href="#" onclick="loadBrands(${data.current_page - 1}); return false;">Trước</a></li>`;
                }

                for (let i = 1; i <= data.last_page; i++) {
                    const active = i === data.current_page ? 'active' : '';
                    html += `<li class="page-item ${active}"><a class="page-link" href="#" onclick="loadBrands(${i}); return false;">${i}</a></li>`;
                }

                if (data.current_page < data.last_page) {
                    html += `<li class="page-item"><a class="page-link" href="#" onclick="loadBrands(${data.current_page + 1}); return false;">Sau</a></li>`;
                }

                html += '</ul></nav>';
                paginationDiv.innerHTML = html;
            }

            const tableDiv = document.getElementById('brands-table');
            const existingPagination = tableDiv.querySelector('.d-flex.justify-content-center');
            if (existingPagination) existingPagination.remove();
            tableDiv.appendChild(paginationDiv);
        }

        function showAddModal() {
            document.getElementById('modal-title').textContent = 'Thêm Thương hiệu';
            document.getElementById('brand-form').reset();
            document.getElementById('brand-id').value = '';
            modal = new bootstrap.Modal(document.getElementById('brandModal'));
            modal.show();
        }

        async function showEditModal(id) {
            try {
                const response = await fetch(`${API_URL}/${id}`);
                const result = await response.json();
                const brand = result.data;

                document.getElementById('modal-title').textContent = 'Sửa Thương hiệu';
                document.getElementById('brand-id').value = brand.id;
                document.getElementById('brand-name').value = brand.brand_name;
                document.getElementById('brand-description').value = brand.description || '';

                modal = new bootstrap.Modal(document.getElementById('brandModal'));
                modal.show();

            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        async function saveBrand() {
            try {
                const id = document.getElementById('brand-id').value;
                const name = document.getElementById('brand-name').value.trim();
                const description = document.getElementById('brand-description').value.trim();
                const logoFile = document.getElementById('brand-logo').files[0];

                if (!name) {
                    showAlert('Vui lòng nhập tên thương hiệu', 'warning');
                    return;
                }

                const formData = new FormData();
                formData.append('brand_name', name);
                formData.append('description', description);

                if (logoFile) formData.append('logo_image', logoFile);

                let url = API_URL;
                if (id) {
                    url = `${API_URL}/${id}`;
                    formData.append('_method', 'PUT');
                }

                const response = await fetch(url, {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Không thể lưu dữ liệu');
                }

                modal.hide();
                showAlert(id ? 'Cập nhật thành công!' : 'Thêm mới thành công!');
                loadBrands(currentPage);

            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        async function deleteBrand(id) {
            if (!confirm('Bạn có chắc muốn xóa?')) return;

            try {
                const response = await fetch(`${API_URL}/${id}`, { method: 'DELETE' });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.error || 'Không thể xóa');
                }

                showAlert('Xóa thành công!');
                loadBrands(currentPage);

            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            loadBrands();
        });

        document.getElementById('search-input').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') searchBrands();
        });
    </script>
</body>
</html>
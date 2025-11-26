<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tài khoản</title>
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
        .badge-role {
            padding: 0.35rem 0.65rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .role-admin { background-color: #fef3c7; color: #92400e; }
        .role-user { background-color: #dbeafe; color: #1e40af; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <p class="text-muted mb-0">Admin / Quản lý tài khoản</p>
            <h2 class="mb-0">Quản lý Tài khoản</h2>
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- Alert -->
            <div id="alert-container"></div>

            <!-- Filter & Search -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <input type="text" id="search-input" class="form-control" placeholder="Tìm username, email, họ tên...">
                        </div>
                        <div class="col-md-3">
                            <select id="role-filter" class="form-select">
                                <option value="">Tất cả vai trò</option>
                                <option value="1">Người dùng</option>
                                <option value="2">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" onclick="loadUsers()">
                                <i class="fas fa-search me-2"></i>Tìm kiếm
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Danh sách Tài khoản</h5>
                </div>
                <div class="card-body">
                    <div id="loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="users-table" class="d-none">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Họ tên</th>
                                        <th>Vai trò</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="users-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết Tài khoản</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="user-details"></div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa Tài khoản</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="user-form">
                        <input type="hidden" id="user-id">
                        <div class="mb-3">
                            <label for="user-email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="user-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="user-fullname" class="form-label">Họ tên</label>
                            <input type="text" class="form-control" id="user-fullname">
                        </div>
                        <div class="mb-3">
                            <label for="user-role" class="form-label">Vai trò <span class="text-danger">*</span></label>
                            <select class="form-select" id="user-role" required>
                                <option value="1">Người dùng</option>
                                <option value="2">Admin</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="updateUser()">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_BASE_URL = 'http://localhost:8000/admin';

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

        function getRoleBadge(role) {
            if (role === 2) {
                return '<span class="badge-role role-admin"><i class="fas fa-crown me-1"></i>Admin</span>';
            }
            return '<span class="badge-role role-user"><i class="fas fa-user me-1"></i>Người dùng</span>';
        }

        async function loadUsers() {
            try {
                document.getElementById('loading').classList.remove('d-none');
                document.getElementById('users-table').classList.add('d-none');

                const search = document.getElementById('search-input').value;
                const role = document.getElementById('role-filter').value;
                
                let url = `${API_BASE_URL}/users?`;
                if (search) url += `search=${encodeURIComponent(search)}&`;
                if (role) url += `role=${role}`;

                const response = await fetch(url);
                if (!response.ok) throw new Error('Không thể tải dữ liệu');

                const result = await response.json();
                const users = result.data?.data || result.data || [];

                const tbody = document.getElementById('users-tbody');
                tbody.innerHTML = '';

                if (users.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center">Không có tài khoản nào</td></tr>';
                } else {
                    users.forEach(user => {
                        const row = `
                            <tr>
                                <td>${user.id}</td>
                                <td><strong>${user.username}</strong></td>
                                <td>${user.email}</td>
                                <td>${user.fullname || '<span class="text-muted">Chưa cập nhật</span>'}</td>
                                <td>${getRoleBadge(user.role)}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="viewUser(${user.id})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="editUser(${user.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                        tbody.innerHTML += row;
                    });
                }

                document.getElementById('loading').classList.add('d-none');
                document.getElementById('users-table').classList.remove('d-none');

            } catch (error) {
                showAlert('Lỗi khi tải dữ liệu: ' + error.message, 'danger');
                document.getElementById('loading').classList.add('d-none');
            }
        }

        async function viewUser(id) {
            try {
                const response = await fetch(`${API_BASE_URL}/users/${id}`);
                const result = await response.json();
                const user = result.data;

                let ordersHtml = '';
                if (user.orders && user.orders.length > 0) {
                    ordersHtml = `<h6 class="mt-4">Lịch sử đơn hàng (${user.orders.length}):</h6><div class="table-responsive"><table class="table table-sm"><thead><tr><th>Mã đơn</th><th>Tổng tiền</th><th>Trạng thái</th></tr></thead><tbody>`;
                    user.orders.slice(0, 5).forEach(order => {
                        const statusMap = { 1: 'Chờ xử lý', 2: 'Đang xử lý', 3: 'Đang giao', 4: 'Đã giao', 5: 'Đã hủy' };
                        ordersHtml += `<tr><td><strong>${order.code}</strong></td><td>${order.total_amount?.toLocaleString('vi-VN')} đ</td><td>${statusMap[order.status] || 'N/A'}</td></tr>`;
                    });
                    ordersHtml += '</tbody></table></div>';
                } else {
                    ordersHtml = '<p class="text-muted mt-3">Chưa có đơn hàng nào</p>';
                }

                document.getElementById('user-details').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID:</strong> ${user.id}</p>
                            <p><strong>Username:</strong> ${user.username}</p>
                            <p><strong>Email:</strong> ${user.email}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Họ tên:</strong> ${user.fullname || '<span class="text-muted">Chưa cập nhật</span>'}</p>
                            <p><strong>Vai trò:</strong> ${getRoleBadge(user.role)}</p>
                        </div>
                    </div>
                    ${ordersHtml}`;

                new bootstrap.Modal(document.getElementById('viewUserModal')).show();
            } catch (error) {
                showAlert('Lỗi khi xem chi tiết: ' + error.message, 'danger');
            }
        }

        async function editUser(id) {
            try {
                const response = await fetch(`${API_BASE_URL}/users/${id}`);
                const result = await response.json();
                const user = result.data;

                document.getElementById('user-id').value = user.id;
                document.getElementById('user-email').value = user.email;
                document.getElementById('user-fullname').value = user.fullname || '';
                document.getElementById('user-role').value = user.role;

                new bootstrap.Modal(document.getElementById('editUserModal')).show();
            } catch (error) {
                showAlert('Lỗi khi tải thông tin: ' + error.message, 'danger');
            }
        }

        async function updateUser() {
            try {
                const id = document.getElementById('user-id').value;
                const email = document.getElementById('user-email').value.trim();
                const fullname = document.getElementById('user-fullname').value.trim();
                const role = document.getElementById('user-role').value;

                if (!email) {
                    showAlert('Vui lòng nhập email', 'warning');
                    return;
                }

                const response = await fetch(`${API_BASE_URL}/users/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, fullname: fullname || null, role: parseInt(role) })
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.error || 'Cập nhật thất bại');
                }

                bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                showAlert('Cập nhật tài khoản thành công!');
                loadUsers();
            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        async function deleteUser(id) {
            if (!confirm('Bạn có chắc muốn xóa tài khoản này?\n\nLưu ý: Không thể xóa nếu tài khoản có đơn hàng.')) return;

            try {
                const response = await fetch(`${API_BASE_URL}/users/${id}`, { method: 'DELETE' });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.error || 'Xóa thất bại');
                }

                showAlert('Xóa tài khoản thành công!');
                loadUsers();
            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        document.addEventListener('DOMContentLoaded', loadUsers);
        document.getElementById('search-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') loadUsers();
        });
    </script>
</body>
</html>
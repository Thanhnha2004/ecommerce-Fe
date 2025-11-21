<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn hàng</title>
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
        .badge-status {
            padding: 0.35rem 0.65rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-1 { background-color: #fef3c7; color: #92400e; }
        .status-2 { background-color: #dbeafe; color: #1e40af; }
        .status-3 { background-color: #e0e7ff; color: #3730a3; }
        .status-4 { background-color: #d1fae5; color: #065f46; }
        .status-5 { background-color: #fee2e2; color: #991b1b; }
        .modal-backdrop.show { opacity: 0.5; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">Admin Panel</div>
        <nav>
            <a href="dashboard.php">
                <i class="fas fa-chart-line me-2"></i> Dashboard
            </a>
            <a href="product.php">
                <i class="fas fa-box me-2"></i> Quản lý sản phẩm
            </a>
            <a href="brand.php">
                <i class="fas fa-tag me-2"></i> Quản lý thương hiệu
            </a>
            <a href="order.php" class="active">
                <i class="fas fa-shopping-cart me-2"></i> Quản lý đơn hàng
            </a>
            <a href="user.php">
                <i class="fas fa-users me-2"></i> Quản lý tài khoản
            </a>
            <a href="category.php">
                <i class="fas fa-list me-2"></i> Quản lý loại sản phẩm
            </a>
            <a href="#" class="text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <p class="text-muted mb-0">Admin / Quản lý đơn hàng</p>
            <h2 class="mb-0">Quản lý Đơn hàng</h2>
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
                            <input type="text" id="search-input" class="form-control" placeholder="Tìm mã đơn, tên, SĐT...">
                        </div>
                        <div class="col-md-3">
                            <select id="status-filter" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="1">Chờ xử lý</option>
                                <option value="2">Đang xử lý</option>
                                <option value="3">Đang giao</option>
                                <option value="4">Đã giao</option>
                                <option value="5">Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" onclick="loadOrders()">
                                <i class="fas fa-search me-2"></i>Tìm kiếm
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Danh sách Đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div id="loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="orders-table" class="d-none">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Khách hàng</th>
                                        <th>SĐT</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="orders-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Order Modal -->
    <div class="modal fade" id="viewOrderModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết Đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="order-details"></div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập nhật Trạng thái</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="update-order-id">
                    <div class="mb-3">
                        <label class="form-label">Trạng thái đơn hàng</label>
                        <select id="update-status" class="form-select">
                            <option value="1">Chờ xử lý</option>
                            <option value="2">Đang xử lý</option>
                            <option value="3">Đang giao</option>
                            <option value="4">Đã giao</option>
                            <option value="5">Đã hủy</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="updateOrderStatus()">Cập nhật</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_BASE_URL = 'http://localhost:8000/admin';

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

        // Get status badge
        function getStatusBadge(status) {
            const statusMap = {
                1: { text: 'Chờ xử lý', class: 'status-1' },
                2: { text: 'Đang xử lý', class: 'status-2' },
                3: { text: 'Đang giao', class: 'status-3' },
                4: { text: 'Đã giao', class: 'status-4' },
                5: { text: 'Đã hủy', class: 'status-5' }
            };
            const s = statusMap[status] || { text: 'Không xác định', class: '' };
            return `<span class="badge-status ${s.class}">${s.text}</span>`;
        }

        // Load orders
        async function loadOrders() {
            try {
                document.getElementById('loading').classList.remove('d-none');
                document.getElementById('orders-table').classList.add('d-none');

                const search = document.getElementById('search-input').value;
                const status = document.getElementById('status-filter').value;
                
                let url = `${API_BASE_URL}/orders?`;
                if (search) url += `search=${encodeURIComponent(search)}&`;
                if (status) url += `status=${status}`;

                const response = await fetch(url);
                if (!response.ok) throw new Error('Không thể tải dữ liệu');

                const result = await response.json();
                const orders = result.data?.data || result.data || [];

                const tbody = document.getElementById('orders-tbody');
                tbody.innerHTML = '';

                if (orders.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center">Không có đơn hàng nào</td></tr>';
                } else {
                    orders.forEach(order => {
                        const row = `
                            <tr>
                                <td><strong>${order.code}</strong></td>
                                <td>${order.customer_name}</td>
                                <td>${order.customer_phone}</td>
                                <td><strong class="text-danger">${formatCurrency(order.total_amount)}</strong></td>
                                <td>${getStatusBadge(order.status)}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="viewOrder(${order.id})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="openUpdateStatus(${order.id}, ${order.status})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteOrder(${order.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                        tbody.innerHTML += row;
                    });
                }

                document.getElementById('loading').classList.add('d-none');
                document.getElementById('orders-table').classList.remove('d-none');

            } catch (error) {
                console.error('Error:', error);
                showAlert('Lỗi khi tải dữ liệu: ' + error.message, 'danger');
                document.getElementById('loading').classList.add('d-none');
            }
        }

        // View order details
        async function viewOrder(id) {
            try {
                const response = await fetch(`${API_BASE_URL}/orders/${id}`);
                const result = await response.json();
                const order = result.data;

                let itemsHtml = '';
                if (order.items && order.items.length > 0) {
                    itemsHtml = '<h6 class="mt-3">Chi tiết sản phẩm:</h6><table class="table table-sm">';
                    itemsHtml += '<thead><tr><th>Sản phẩm</th><th>SL</th><th>Đơn giá</th><th>Tổng</th></tr></thead><tbody>';
                    order.items.forEach(item => {
                        itemsHtml += `<tr>
                            <td>${item.product?.name || 'N/A'}</td>
                            <td>${item.quantity}</td>
                            <td>${formatCurrency(item.price)}</td>
                            <td><strong>${formatCurrency(item.price * item.quantity)}</strong></td>
                        </tr>`;
                    });
                    itemsHtml += '</tbody></table>';
                }

                document.getElementById('order-details').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Mã đơn:</strong> ${order.code}</p>
                            <p><strong>Khách hàng:</strong> ${order.customer_name}</p>
                            <p><strong>SĐT:</strong> ${order.customer_phone}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Địa chỉ:</strong> ${order.customer_address || 'N/A'}</p>
                            <p><strong>Trạng thái:</strong> ${getStatusBadge(order.status)}</p>
                            <p><strong>Tổng tiền:</strong> <span class="text-danger fw-bold">${formatCurrency(order.total_amount)}</span></p>
                        </div>
                    </div>
                    ${order.note ? `<p><strong>Ghi chú:</strong> ${order.note}</p>` : ''}
                    ${itemsHtml}
                `;

                new bootstrap.Modal(document.getElementById('viewOrderModal')).show();
            } catch (error) {
                showAlert('Lỗi khi xem chi tiết: ' + error.message, 'danger');
            }
        }

        // Open update status modal
        function openUpdateStatus(id, currentStatus) {
            document.getElementById('update-order-id').value = id;
            document.getElementById('update-status').value = currentStatus;
            new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
        }

        // Update order status
        async function updateOrderStatus() {
            try {
                const id = document.getElementById('update-order-id').value;
                const status = document.getElementById('update-status').value;

                const response = await fetch(`${API_BASE_URL}/orders/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ status: parseInt(status) })
                });

                if (!response.ok) throw new Error('Cập nhật thất bại');

                bootstrap.Modal.getInstance(document.getElementById('updateStatusModal')).hide();
                showAlert('Cập nhật trạng thái thành công!');
                loadOrders();
            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        // Delete order
        async function deleteOrder(id) {
            if (!confirm('Bạn có chắc muốn xóa đơn hàng này?')) return;

            try {
                const response = await fetch(`${API_BASE_URL}/orders/${id}`, {
                    method: 'DELETE'
                });

                if (!response.ok) throw new Error('Xóa thất bại');

                showAlert('Xóa đơn hàng thành công!');
                loadOrders();
            } catch (error) {
                showAlert('Lỗi: ' + error.message, 'danger');
            }
        }

        // Load on page load
        document.addEventListener('DOMContentLoaded', loadOrders);

        // Search on Enter
        document.getElementById('search-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') loadOrders();
        });
    </script>
</body>
</html>
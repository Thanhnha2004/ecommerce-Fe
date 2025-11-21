<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
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
        .sidebar nav a:hover {
            background-color: #334155;
        }
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
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
        }
        .stat-card .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .stat-card .value {
            font-size: 2rem;
            font-weight: bold;
            color: #1e293b;
        }
        .stat-card .label {
            color: #64748b;
            font-size: 0.875rem;
        }
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 400px;
        }
        .spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">Admin Panel</div>
        <nav>
            <a href="dashboard.php" class="active">
                <i class="fas fa-chart-line me-2"></i> Dashboard
            </a>
            <a href="product.php">
                <i class="fas fa-box me-2"></i> Quản lý sản phẩm
            </a>
            <a href="brand.php">
                <i class="fas fa-tag me-2"></i> Quản lý thương hiệu
            </a>
            <a href="order.php">
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
            <p class="text-muted mb-0">Admin /</p>
            <h2 class="mb-0">Dashboard</h2>
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- Loading State -->
            <div id="loading" class="loading">
                <div>
                    <div class="spinner mx-auto mb-3"></div>
                    <p class="text-muted">Đang tải dữ liệu...</p>
                </div>
            </div>

            <!-- Error Alert -->
            <div id="error-alert" class="alert alert-danger d-none" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span id="error-message"></span>
            </div>

            <!-- Stats Container -->
            <div id="stats-container" class="d-none">
                <div class="row g-4 mb-4">
                    <!-- Người dùng -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="label mb-2">Người dùng</div>
                                    <div class="value" id="users-count">0</div>
                                </div>
                                <div class="icon-box" style="background-color: #dbeafe; color: #3b82f6;">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sản phẩm -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="label mb-2">Sản phẩm</div>
                                    <div class="value" id="products-count">0</div>
                                </div>
                                <div class="icon-box" style="background-color: #dcfce7; color: #22c55e;">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Đơn đặt -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="label mb-2">Đơn đặt</div>
                                    <div class="value" id="orders-count">0</div>
                                </div>
                                <div class="icon-box" style="background-color: #fef3c7; color: #f59e0b;">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tiền nhập -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="label mb-2">Tiếng tiền nhập</div>
                                    <div class="value" id="revenue-amount" style="font-size: 1.5rem;">0 đ</div>
                                </div>
                                <div class="icon-box" style="background-color: #fee2e2; color: #ef4444;">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Thông tin hệ thống</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted mb-1">API Endpoint:</p>
                                <p class="fw-bold" id="api-endpoint">http://localhost:8000/admin</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1">Trạng thái:</p>
                                <p class="fw-bold text-success">
                                    <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>
                                    Đang hoạt động
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center text-muted mt-4">
                <small>Admin Dashboard ©2024</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Thay đổi URL này nếu Laravel chạy ở port khác
        const API_BASE_URL = 'http://localhost:8000/admin';

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + ' đ';
        }

        // Show error
        function showError(message) {
            document.getElementById('error-message').innerHTML = message;
            document.getElementById('error-alert').classList.remove('d-none');
            document.getElementById('loading').classList.add('d-none');
        }

        // Test API connection
        async function testAPIConnection() {
            try {
                console.log('Testing API connection to:', API_BASE_URL);
                const response = await fetch(`${API_BASE_URL}/users`);
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                return response.ok;
            } catch (error) {
                console.error('API Connection Error:', error);
                return false;
            }
        }

        // Fetch dashboard data
        async function fetchDashboardData() {
            try {
                console.log('Fetching dashboard data...');
                
                // Test connection first
                const isConnected = await testAPIConnection();
                if (!isConnected) {
                    throw new Error('Không thể kết nối đến API');
                }

                // Fetch all data in parallel
                const [usersRes, productsRes, ordersRes] = await Promise.all([
                    fetch(`${API_BASE_URL}/users`),
                    fetch(`${API_BASE_URL}/products`),
                    fetch(`${API_BASE_URL}/orders`)
                ]);

                console.log('Users response:', usersRes.status);
                console.log('Products response:', productsRes.status);
                console.log('Orders response:', ordersRes.status);

                // Check if all requests succeeded
                if (!usersRes.ok || !productsRes.ok || !ordersRes.ok) {
                    throw new Error('Một số API endpoint không phản hồi');
                }

                const usersData = await usersRes.json();
                const productsData = await productsRes.json();
                const ordersData = await ordersRes.json();

                console.log('Users data:', usersData);
                console.log('Products data:', productsData);
                console.log('Orders data:', ordersData);

                // Calculate totals
                const totalUsers = usersData.data?.total || usersData.data?.length || 0;
                const totalProducts = productsData.data?.total || productsData.data?.length || 0;
                const totalOrders = ordersData.data?.total || ordersData.data?.length || 0;

                // Calculate revenue
                let totalRevenue = 0;
                const orders = ordersData.data?.data || ordersData.data || [];
                
                if (Array.isArray(orders)) {
                    totalRevenue = orders.reduce((sum, order) => {
                        return sum + (parseFloat(order.total_amount) || 0);
                    }, 0);
                }

                // Update UI
                document.getElementById('users-count').textContent = totalUsers;
                document.getElementById('products-count').textContent = totalProducts;
                document.getElementById('orders-count').textContent = totalOrders;
                document.getElementById('revenue-amount').textContent = formatCurrency(totalRevenue);

                // Hide loading, show stats
                document.getElementById('loading').classList.add('d-none');
                document.getElementById('stats-container').classList.remove('d-none');

                console.log('Dashboard data loaded successfully!');

            } catch (error) {
                console.error('Error fetching data:', error);
                showError(`
                    <strong>Lỗi kết nối API!</strong><br>
                    <small>Chi tiết: ${error.message}</small><br><br>
                    <strong>Các bước kiểm tra:</strong><br>
                    1. Đảm bảo Laravel server đang chạy: <code>php artisan serve</code><br>
                    2. Kiểm tra URL API: <code>${API_BASE_URL}</code><br>
                    3. Kiểm tra CORS trong file <code>config/cors.php</code><br>
                    4. Xem Console (F12) để biết thêm chi tiết
                `);
            }
        }

        // Load data when page loads
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Page loaded, fetching dashboard data...');
            fetchDashboardData();
        });
    </script>
</body>
</html>
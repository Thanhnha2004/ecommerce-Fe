<?php
// Thông tin kết nối CSDL
$servername = "localhost"; // Địa chỉ máy chủ CSDL (thường là localhost)
$username = "root";       // Tên người dùng CSDL mặc định của XAMPP
$password = "";           // Mật khẩu CSDL (mặc định XAMPP là rỗng, nếu bạn đã đặt mật khẩu, hãy nhập vào đây)
$dbname = "ecommerce"; // Tên CSDL bạn vừa tạo ở bước 2

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

mysqli_set_charset($conn, "utf8mb4");

function formatPrice($price)
{
    return number_format($price, 0, ',', '.') . 'đ';
}

function getHotProducts($limit = 5)
{
    $url = "http://localhost:8000/api/home/hotProducts";

    $json = file_get_contents($url);

    if (!$json) {
        return []; // nếu API lỗi thì trả mảng rỗng
    }

    $data = json_decode($json, true);

    // Lấy giới hạn theo $limit
    return array_slice($data, 0, $limit);
}

function getBrands($limit = 6)
{
    $url = "http://localhost:8000/api/home/brands";

    $json = file_get_contents($url);

    if (!$json) {
        return [];
    }

    $data = json_decode($json, true);

    return array_slice($data, 0, $limit);
}

function getMakeupProducts($limit = 8)
{
    $url = "http://localhost:8000/api/home/makeupProducts";

    $json = file_get_contents($url);

    if (!$json) {
        return [];
    }

    $data = json_decode($json, true);

    return array_slice($data, 0, $limit);
}

function getLipstickProducts($limit = 8)
{
    $url = "http://localhost:8000/api/home/lipstickProducts";

    $json = file_get_contents($url);

    if (!$json) {
        return [];
    }

    $data = json_decode($json, true);

    return array_slice($data, 0, $limit);
}

function getSkincareProducts($limit = 8)
{
    $url = "http://localhost:8000/api/home/skincareProducts";

    $json = file_get_contents($url);

    if (!$json) {
        return [];
    }

    $data = json_decode($json, true);

    return array_slice($data, 0, $limit);
}


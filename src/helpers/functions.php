<?php
// Thông tin kết nối CSDL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

mysqli_set_charset($conn, "utf8mb4");

// Hàm helper để gọi API bằng cURL
function callAPI($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $response) {
        return json_decode($response, true);
    }
    
    return [];
}

function formatPrice($price)
{
    return number_format($price, 0, ',', '.') . 'đ';
}

function getHotProducts($limit = 5)
{
    $url = "http://localhost:8000/api/home/hotProducts";
    $data = callAPI($url);
    return array_slice($data, 0, $limit);
}

function getBrands($limit = 6)
{
    $url = "http://localhost:8000/api/home/brands";
    $data = callAPI($url);
    return array_slice($data, 0, $limit);
}

function getMakeupProducts($limit = 8)
{
    $url = "http://localhost:8000/api/home/makeupProducts";
    $data = callAPI($url);
    return array_slice($data, 0, $limit);
}

function getLipstickProducts($limit = 8)
{
    $url = "http://localhost:8000/api/home/lipstickProducts";
    $data = callAPI($url);
    return array_slice($data, 0, $limit);
}

function getSkincareProducts($limit = 8)
{
    $url = "http://localhost:8000/api/home/skincareProducts";
    $data = callAPI($url);
    return array_slice($data, 0, $limit);
}
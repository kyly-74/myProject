<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "ltlaixe_database");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Không thể kết nối: " . $conn->connect_error);
}

// Thiết lập charset để dùng tiếng Việt
$conn->set_charset("utf8");
?>

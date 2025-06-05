<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user']['id']) || !isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['message'] = 'Yêu cầu không hợp lệ';
    header('Location: index.php?sidebar=logout&show=change_password');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'trafficedu');
if ($conn->connect_error) {
    $_SESSION['message'] = 'Lỗi server';
    header('Location: index.php?sidebar=logout&show=change_password');
    exit;
}

$currentPassword = $_POST['currentPassword'] ?? '';
$newPassword = $_POST['newPassword'] ?? '';
$confirmNewPassword = $_POST['confirmNewPassword'] ?? '';

if (!$currentPassword || !$newPassword || !$confirmNewPassword) {
    $_SESSION['message'] = 'Thiếu thông tin';
    header('Location: index.php?sidebar=logout&show=change_password');
    exit;
}

if ($newPassword !== $confirmNewPassword) {
    $_SESSION['message'] = 'Mật khẩu mới không khớp';
    header('Location: index.php?sidebar=logout&show=change_password');
    exit;
}

$userId = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || !password_verify($currentPassword, $user['password'])) {
    $_SESSION['message'] = 'Mật khẩu hiện tại không đúng';
    header('Location: index.php?sidebar=logout&show=change_password');
    $stmt->close();
    exit;
}

$hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->bind_param("si", $hashedNewPassword, $userId);

if ($stmt->execute()) {
    $_SESSION['message'] = 'Đổi mật khẩu thành công';
    header('Location: index.php');
} else {
    $_SESSION['message'] = 'Đổi mật khẩu thất bại';
    header('Location: index.php?sidebar=logout&show=change_password');
}

$stmt->close();
$conn->close();
?>
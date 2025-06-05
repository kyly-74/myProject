<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['message'] = 'Yêu cầu không hợp lệ';
    header('Location: index.php?sidebar=auth');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'driving_test_db');
if ($conn->connect_error) {
    $_SESSION['message'] = 'Lỗi server';
    header('Location: index.php?sidebar=auth');
    exit;
}

$action = $_POST['action'] ?? '';
if ($action === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if (!$username || !$password) {
        $_SESSION['message'] = 'Thiếu thông tin';
        header('Location: index.php?sidebar=auth&tab=login');
        exit;
    }

    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email']];
        $_SESSION['message'] = 'Đăng nhập thành công';
        header('Location: index.php');
    } else {
        $_SESSION['message'] = 'Tên đăng nhập hoặc mật khẩu không đúng';
        header('Location: index.php?sidebar=auth&tab=login');
    }
    $stmt->close();
} elseif ($action === 'register') {
    $name = $_POST['name'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if (!$name || !$username || !$email || !$password || !$confirmPassword) {
        $_SESSION['message'] = 'Thiếu thông tin';
        header('Location: index.php?sidebar=auth&tab=register');
        exit;
    }

    if ($password !== $confirmPassword) {
        $_SESSION['message'] = 'Mật khẩu không khớp';
        header('Location: index.php?sidebar=auth&tab=register');
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['message'] = 'Tên đăng nhập hoặc email đã tồn tại';
        header('Location: index.php?sidebar=auth&tab=register');
        $stmt->close();
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $username, $email, $hashedPassword);
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Đăng ký thành công';
        header('Location: index.php?sidebar=auth&tab=login');
    } else {
        $_SESSION['message'] = 'Đăng ký thất bại';
        header('Location: index.php?sidebar=auth&tab=register');
    }
    $stmt->close();
} else {
    $_SESSION['message'] = 'Hành động không hợp lệ';
    header('Location: index.php?sidebar=auth');
}

$conn->close();
?>
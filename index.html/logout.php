<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user']['id']) || !isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['message'] = 'Yêu cầu không hợp lệ';
    header('Location: index.php');
    exit;
}

session_unset();
session_destroy();
$_SESSION['message'] = 'Đăng xuất thành công';
header('Location: index.php');
exit;
?>
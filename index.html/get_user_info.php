<?php
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user']['id']) || !isset($input['csrf_token']) || $input['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['message' => 'Yêu cầu không hợp lệ']);
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'trafficedu');
if ($conn->connect_error) {
    echo json_encode(['message' => 'Lỗi server']);
    exit;
}

$userId = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($user) {
    echo json_encode(['name' => htmlspecialchars($user['name']), 'email' => htmlspecialchars($user['email'])]);
} else {
    echo json_encode(['message' => 'Không tìm thấy thông tin']);
}

$stmt->close();
$conn->close();
?>
<?php
$examId = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 1;

$conn = new mysqli("localhost", "root", "", "ltlaixe_database");
$conn->set_charset("utf8");

// Xử lý nếu kết nối thất bại
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xác định truy vấn và tham số tùy theo đề thường hay đề liệt
if ($examId >= 1 && $examId <= 20) {
    // Đề thường: 25 câu, set_id = 1
    $start = ($examId - 1) * 25;
    $limit = 25;

    $sql = "SELECT 
                q.question_id,
                q.question_text, 
                q.question_image, 
                a.answer_text, 
                a.explanation 
            FROM 
                questions q 
            JOIN 
                answers a ON q.question_id = a.question_id 
            WHERE 
                a.is_correct = 1 AND q.set_id = 1
            ORDER BY q.question_id
            LIMIT ?, ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed (đề thường): " . $conn->error);
    }
    $stmt->bind_param("ii", $start, $limit);

} elseif ($examId == 21) {
    // Đề liệt: 20 câu đầu tiên của set_id = 2
    $start = 0;
    $limit = 20;

    $sql = "SELECT 
                q.question_id,
                q.question_text, 
                q.question_image, 
                a.answer_text, 
                a.explanation 
            FROM 
                questions q 
            JOIN 
                answers a ON q.question_id = a.question_id 
            WHERE 
                a.is_correct = 1 AND q.set_id = 2
            ORDER BY q.question_id
            LIMIT ?, ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed (đề liệt): " . $conn->error);
    }
    $stmt->bind_param("ii", $start, $limit);

} else {
    echo json_encode(["error" => "exam_id không hợp lệ"]);
    exit;
}


// Thực thi và xử lý kết quả
$stmt->execute();
$result = $stmt->get_result();

$questions = array();
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}
// If you want to debug in PHP, use the following instead:
 foreach ($questions as $index => $q) {
     error_log("Câu " . ($index + 1) . ": " . $q['question_image']);
}

// Xuất dữ liệu JSON
echo json_encode($questions);
?>

<?php
include '../Widget/mysql_connect.php';
isset($_GET['set_id']) ? $set_id = intval($_GET['set_id']) : $set_id = 40;

$limit = 50;
$critical_questions = [
    40 => [651, 700]
];

if (!isset($critical_questions[$set_id])) {
    echo "<p>Không tìm thấy bộ câu hỏi phù hợp.</p>";
    exit;
}

list($start_id, $end_id) = $critical_questions[$set_id];

$stmt = $conn->prepare("
    SELECT q.*, a.answer_text, a.explanation 
    FROM questions q
    JOIN answers a ON q.question_id = a.question_id
    WHERE a.is_correct = 1 AND q.question_id BETWEEN ? AND ?
    ORDER BY q.question_id
    LIMIT ?
");
$stmt->bind_param("iii", $start_id, $end_id, $limit);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc())
    $questions[] = $row;

$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../style/middle.css"/>
</head>

<body>
  <div class="main-content">
        <?php include '../Widget/header.php'; ?> 
    <h2>BỘ ĐỀ ÔN TẬP THI THỬ BẰNG LÁI XE A1 </h2>
    <div class="exam-container">
      <!-- Cột bên trái -->
     
      <!-- Cột bên phải -->
        <?php
        $count = 1;
        if (!empty($questions)) {
            foreach ($questions as $q) {
                echo '<div class="question-box">';
                echo "<h3>Câu $count: {$q["question_text"]}</h3>";
                if (!empty($q['question_image'])) {
                    // Lấy tên tệp từ đường dẫn
                    $filename = basename($q['question_image']);
                    $correctPath = "../assets/img/" . $filename;
                    // Kiem tra file ton tai tren server(tranh duong dan sai)
                    if(file_exists($correctPath))
                    {
                        echo "<img src='$correctPath' alt='Hình minh họa' style='max-width: 500px; display:block; margin-top:10px;'>";
                    }
                    
                }
                echo "<h3>✅ Đáp án đúng: <span style='color: green'>{$q["answer_text"]}</span></h3>";
                echo "<h3>💡 Giải thích: {$q['explanation']}</h3>";
                echo '</div>';
                $count++;
            }
        } else {
            echo "<p>Không có câu hỏi nào.</p>";
        }
        ?>
    
   
    </div>
    <div class="finish-btn">
        <button onclick="location.href='chondeA2.php'">Kết thúc</button>
      </div>
     <?php include '../Widget/footer.php'; ?>

  </div>
 
</body>
</html>
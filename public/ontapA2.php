<?php
include "../Widget/mysql_connect.php";
isset($_GET['set_id']) ? $set_id = intval($_GET['set_id']) : $set_id = 1;
// Kiểm tra kết nối


$limit = 25;

$critical_questions_by_set = [
        22 => [202, 203, 206, 208],
        23 => [227, 234],
        24 => [252, 255, 256, 258, 260],
        25 => [277, 278],
        26 => [302, 310, 325],
        27 => [327],
        28 => [352, 353, 356],
        29 => [377],
        30 => [402, 403, 410],
        31 => [427, 428, 430, 435],
        32 => [452, 453],
        33 => [477, 482, 484],
        34 => [503, 509],
        35 => [528, 529, 532],
        36 => [552, 553],
        37 => [578, 580, 582],
        38 => [601, 602, 603, 609],
        39 => [626, 627], 
];

$question_ranges = [
        22 => [201, 225],
        23 => [226, 250],
        24 => [251, 275],
        25 => [276, 300],
        26 => [301, 325],
        27 => [326, 350],
        28 => [351, 375],
        29 => [376, 400],
        30 => [401, 425],
        31 => [426, 450],
        32 => [451, 475],
        33 => [476, 500],
        34 => [501, 525],
        35 => [526, 550],
        36 => [551, 575],
        37 => [576, 600],
        38 => [601, 625],
        39 => [626, 650], 
];

$questions = [];

if (isset($question_ranges[$set_id])) {
    list($start_id, $end_id) = $question_ranges[$set_id];

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

    $critical_ids = $critical_questions_by_set[$set_id] ?? [];

    while ($row = $result->fetch_assoc()) {
        $row['is_critical'] = in_array($row['question_id'], $critical_ids) ? 1 : 0;
        $questions[] = $row;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Ôn Tập A1</title>
  <link rel="stylesheet" href="../style/middle.css" />
</head>
<body>
  <div>
    <?php include '../Widget/header.php'; ?>
    <h2>BỘ ÔN TẬP THI THỬ BẰNG LÁI XE A2</h2>
    <div class="exam-container">
      <div class="question-list">
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
    </div>
     <div class="finish-btn">
        <button onclick="location.href='chondeA2.php'">Kết thúc</button>
      </div>
     <?php include '../Widget/footer.php'; ?>

  </div>
</body>
</html>

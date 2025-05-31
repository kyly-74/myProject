
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
include "../Widget/mysql_connect.php";
 $critical_questions = [
        21 => [3, 5, 12, 28, 29, 30, 33, 53, 54, 79, 104, 108, 129, 135, 152, 153, 154, 177, 179, 701]
    ];
    $set_id=21;
    $limit = 20;
     if (!isset($critical_questions[$set_id]))
        return [];

    $ids = implode(',', $critical_questions[$set_id]);

    $sql = "SELECT * 
        FROM questions q 
        JOIN answers a ON q.question_id = a.question_id 
        WHERE q.question_id IN ($ids) 
        AND a.is_correct = 1 
        LIMIT $limit";
    $result = mysqli_query($conn, $sql);

    $questions = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['is_critical'] = 1;
        $questions[] = $row;
        
    }
$count = 1;
foreach($questions as $q) {
    echo '<div class="question-box">';
    echo "<h3>Câu $count: {$q["question_text"]}</h3>";
    if (!empty($q['question_image'])) {
        $filename = basename($q['question_image']);
        $correctPath = "../assets/img/" . $filename;
        if(file_exists($correctPath))
        {
            echo "<img src='$correctPath' alt='Hình minh họa'  style='max-width: 500px; display:block; margin-top:10px;'>";
        }
        
    }
    echo "<h3>✅ Đáp án đúng: <span style='color: green'>{$q["answer_text"]}</span></h3>";
    echo "<h3>💡 Giải thích: {$q['explanation']}</h3>";
    echo '</div>';
    $count++;
}

?>    
    </div>
    <div class="finish-btn">
        <button onclick="location.href='.php'">Kết thúc</button>
      </div>
     <?php include '../Widget/footer.php'; ?>

  </div>
 
</body>
</html>
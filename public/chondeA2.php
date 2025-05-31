<?php
include '../Widget/mysql_connect.php'; // kết nối cơ sở dữ liệu

$category_id = 3;
$category_critical_id = 4;
$limit_18 = 18;

$stmt_18   = $conn->prepare("SELECT * FROM exam_sets WHERE category_id = ? ORDER BY set_id LIMIT ?");
$stmt_18->bind_param("ii", $category_id, $limit_18);
$stmt_18->execute();
$result_18 = $stmt_18->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On thi lai xe</title>
    <link rel="stylesheet" href="../style/style.css" />
</head>

<body>
    
    <div class="main-content">
         <?php include '../Widget/header.php'; ?> 


            <h2>BỘ ÔN TẬP ĐỀ THI THỬ BẰNG LÁI XE A2</h2>
             <div class="button-group">
                <h4>Chọn đề ôn tập:</h4>
             
                   <!-- Các button câu hỏi điểm liệt -->
                <div class="center-button" >
                    <button type="button" onclick="location.href='ontapcaulietA2.php?set_id=40'" class="center-button">
                        50 Câu Hỏi Điểm Liệt
                    </button>
                </div>
                        
                 <div class="exam-grid">
                    
                    <?php
                        $count = 1;
                        if ($result_18->num_rows > 0) {
                            while ($row = $result_18->fetch_assoc()) {
                                if ($count <= 18) {
                                    echo  "
                                        <form action='ontapA2.php' method='get' style='display:inline-block; margin: 8px;'>
                                            <input type='hidden' name='set_id' value='{$row['set_id']}'>
                                           <button type='submit'>Đề {$count}</button> 
                                        </form>
                                    ";
                                    $count++;
                                } else {
                                    break;
                                }
                                
                            }
                        }
                        ?>
                    </div>
                  
            
            </div> 
            <div id="content">
                <p>Hãy lựa chọn đề và bắt đầu ôn tập </p>
            </div>
            <div class="cach" style="gap: 20px;">
                <div class="cach1">
                    <h3>Hướng dẫn:</h3>
                    <p>Chọn đề ôn tập để bắt đầu làm bài.</p>
                    <p>Mỗi đề gồm 20 câu hỏi, thời gian làm bài là 20 phút.</p>
                    <p>Sau khi hoàn thành, bạn có thể xem đáp án và giải thích.</p>
                </div>
            </div>
             <?php include '../Widget/footer.php'; ?>

        </div>

</body>

</html>
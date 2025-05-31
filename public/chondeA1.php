<?php
include '../Widget/mysql_connect.php'; // kết nối cơ sở dữ liệu

$category_id = 1;
$category_critical_id = 2;
$limit_8 = 8;

$stmt_8   = $conn->prepare("SELECT * FROM exam_sets WHERE category_id = ? ORDER BY set_id LIMIT ?");
$stmt_8->bind_param("ii", $category_id, $limit_8);
$stmt_8->execute();
$result_8 = $stmt_8->get_result();
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


            <h2>BỘ ÔN TẬP ĐỀ THI THỬ BẰNG LÁI XE A1</h2>
             <div class="button-group">
                <h4>Chọn đề ôn tập:</h4>
             
                   <!-- Các button câu hỏi điểm liệt -->
                <div class="center-button" >
                    <button type="button" onclick="location.href='ontapcaulietA1.php?set_id=21'" class="center-button">
                        20 Câu Hỏi Điểm Liệt
                    </button>
                </div>
                        
                 <div class="exam-grid">
                    
                    <?php
                        $count = 1;
                        if ($result_8->num_rows > 0) {
                            while ($row = $result_8->fetch_assoc()) {
                                if ($count <= 8) {
                                    echo  "
                                        <form action='ontapA1.php' method='get' style='display:inline-block; margin: 8px;'>
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
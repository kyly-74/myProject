<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>On thi lai xe</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="main-content">
        <div class="banner">
            <div class="banner-content">
                <img src="assets/img/logo.svg" width="150" height="100" alt="Banner Image">
            </div>
            <div class="logo">
                <h4>Luyện Thi Bằng Lái Xe Máy A1 - A2 (2025)</h4>
            </div>
            <div class="hotline">
                <h4> HOTLINE: 0815.62.63.72</h4>
            </div>


        </div>
        <div class="navbar">
            <a href="Test.php" class="active" style="margin-top: 40px">Chọn Phần Ôn Tập</a>
            <a href="#">Ôn Tập A1</a>
            <a href="#">Ôn Tập A2</a>
            <a href='middle.php?exam_id=21'>Ôn 20 câu Điểm Liệt</a>
            <a href="#">Ôn 50 Câu Điểm Liệt</a>
            <a href="#">Ôn 60 Câu Điểm Liệt</a>
        </div>
        <div class="container">

            <h2>BỘ ÔN TẬP ĐỀ THI THỬ BẰNG LÁI XE A1</h2>
            <div class="button-group">
                <h4>Chọn đề thi:</h4>
                <button onclick="location.href='middle.php?exam_id=21'" class="center-button">20 Câu Hỏi Điểm Liệt</button>
                <br><button onclick="location.href='middle.php?exam_id=1'">Đề 1</button>
                <button onclick="location.href='middle.php?exam_id=2'">Đề 2</button>
                <button onclick="location.href='middle.php?exam_id=3'">Đề 3</button>
                <button onclick="location.href='middle.php?exam_id=4'">Đề 4</button>
                <button onclick="location.href='middle.php?exam_id=5'">Đề 5</button>
                <button onclick="location.href='middle.php?exam_id=6'">Đề 6</button>
                <button onclick="location.href='middle.php?exam_id=7'">Đề 7</button>
                <button onclick="location.href='middle.php?exam_id=8'">Đề 8</button>
                <p style="color: red;padding:10px 30px;
        font-size: 17px;"> Lưu ý: 8 x 25 = 200 câu hỏi, hoàn thành 8 bộ đề trên coi như bạn đã ôn trọn vẹn tất cả các câu hỏi ôn thi A1.
                    Tuy nhiên, bạn có thể ôn thêm 20 bộ đề cấu trúc A1 chuẩn của chúng tôi, mỗi bộ đề có 1 câu điểm liệt, cấu trúc sẽ giống với đề thi
                    thật nhất, chỉ khác đề thi thật chọn ngẫu nhiên câu hỏi, không bộ đề nào giống bộ đề nào, còn chúng tôi cố định từng đề để các bạn
                    tiện hỏi đáp hơn.</p>
                <button onclick="loadDe(9)">Đề 9</button>
                <button onclick="loadDe(10)">Đề 10</button>
                <button onclick="loadDe(11)">Đề 11</button>
                <button onclick="loadDe(12)">Đề 12</button>
                <button onclick="loadDe(13)">Đề 13</button>
                <button onclick="loadDe(14)">Đề 14</button>
                <button onclick="loadDe(15)">Đề 15</button>
                <button onclick="loadDe(16)">Đề 16</button>

                <button onclick="loadDe(17)">Đề 17</button>
                <button onclick="loadDe(18)">Đề 18</button>
                <button onclick="loadDe(19)">Đề 19</button>
                <button onclick="loadDe(20)">Đề 20</button>
            </div>
            <div id="content">
                <p>Hãy lựa chọn đề và bắt đầu ôn tập </p>
            </div>
        </div>
        <div class="footer">
            <div class="footer-content">
                <div class="abt-us">
                    <h3>Về Chúng Tôi</h3>
                    <p>Trung Tâm Đào Tạo Lái Xe chuyên </p>
                    <p>cung cấp các khóa học lái xe chất </p>
                    <p>lượng cao, giúp học viên đạt tỉ lệ cao nhất.</p>
                </div>
                <div class="contact">
                    <h3>Liên Hệ</h3>
                    <p>Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM</p>
                    <p>Điện thoại: 0256 3646373</p>
                    <p>Email: info@hoclaixe.vn</p>
                </div>
                <div class="course">
                    <h3>Khóa Học</h3>
                    <p>Bằng Lái Xe A1</p>
                    <p>Bằng Lái Xe A2</p>
                </div>
                <div class="follow">
                    <h3>Theo dõi</h3>
                    <a href="#">Facebook</a>
                    <a href="#">Zalo</a>
                    <a href="#">Youtube</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 Trung Tâm Đào Tạo Lái Xe. All rights reserved.</p>
            </div>
        </div>
    </div>



</body>

</html>
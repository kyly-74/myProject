<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="middle.css" />
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
      <a href="Test.php" class="active" style="margin-top: 40px">Chọn Phần Thi</a>
      <a href="#">Ôn tập A1</a>
      <a href="#">Ôn tập A2</a>
      <a href="#">Ôn tập 20 câu Điểm Liệt</a>
      <a href="#">Ôn tập 50 Câu Điểm Liệt</a>
      
    </div>
    <h2>BỘ ĐỀ ÔN TẬP THI THỬ BẰNG LÁI XE A1 </h2>
    <div class="exam-container">
      <!-- Cột bên trái -->
     
      <!-- Cột bên phải -->
      <div class="question-content">
        <div class='ques'>
          <h3>Câu Hỏi</h3>
        </div>   
        <div class="Ans">
          <h3>Đáp án</h3>
        </div>
        <div class="Expl">
          <h3>Giải thích</h3>
        </div>

      </div>
      
    </div>
    <div class="finish-btn">
        <button onclick="location.href='Test.php'">Kết thúc</button>
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
  <script>
    let currentIndex = 0; // Biến để theo dõi câu hỏi hiện tại
    let questions = []; // Mảng chứa các câu hỏi

    const urlParams = new URLSearchParams(window.location.search);
    const examId = urlParams.get('exam_id'); // Lấy exam_id từ URL

    fetch(`getdata/get_exam_data.php?exam_id=${examId}`)
      .then(res => res.json())
      .then(data => {
        questions = data; // Lưu dữ liệu câu hỏi vào biến questions
          console.log("Dữ liệu nhận được:", data);
       renderAllQuestions(); 

      })
      .catch(err => {
        console.error("Lỗi khi tải dữ liệu:", err);
      });
      function loadExam(examId) {
    fetch('middle.php?exam_id=' + examId)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                document.getElementById('question-container').innerHTML = "<p style='color:red'>" + data.error + "</p>";
                return;
            }
            renderAllQuestions(data);
        })
        .catch(error => {
            console.error("Lỗi khi tải đề:", error);
        });
}
      
function renderAllQuestions() {
  const container = document.querySelector(".question-content");
  container.innerHTML = ""; // Xóa nội dung cũ

  questions.forEach((q, index) => {
    const qDiv = document.createElement("div");
    let imageTag = "";

    if (q.question_image) {
      const filename = q.question_image.split('/').pop();
      const correctPath = "assets/img/" + filename;

      // Tạo thẻ img tạm và kiểm tra xem ảnh có tồn tại không
      const img = new Image();
      img.src = correctPath;
      img.onload = function () {
        // Nếu ảnh tồn tại, thêm vào DOM
        imageTag = `
          <div class="image">
            <img src="${correctPath}" style="max-width: 300px; display:block; margin-top:10px;">
          </div>
        `;

        qDiv.innerHTML = `
          <div class='ques'>
            <h3>Câu ${index + 1}: ${q.question_text}</h3>
          </div>
          ${imageTag}
          <div class="Ans">
            <h3>Đáp án: ${q.answer_text}</h3>
          </div>
          <div class="Expl">
            <h3>Giải thích: ${q.explanation}</h3>
          </div>
          <hr>
        `;
        container.appendChild(qDiv);
      };
      img.onerror = function () {
        // Nếu ảnh không tồn tại, chỉ hiện câu hỏi, không ảnh
        qDiv.innerHTML = `
          <div class='ques'>
            <h3>Câu ${index + 1}: ${q.question_text}</h3>
          </div>
          <div class="Ans">
            <h3>Đáp án: ${q.answer_text}</h3>
          </div>
          <div class="Expl">
            <h3>Giải thích: ${q.explanation}</h3>
          </div>
          <hr>
        `;
        container.appendChild(qDiv);
      };
    } else {
      // Không có ảnh thì hiển thị như bình thường
      qDiv.innerHTML = `
        <div class='ques'>
          <h3>Câu ${index + 1}: ${q.question_text}</h3>
        </div>
        <div class="Ans">
          <h3>Đáp án: ${q.answer_text}</h3>
        </div>
        <div class="Expl">
          <h3>Giải thích: ${q.explanation}</h3>
        </div>
        <hr>
      `;
      container.appendChild(qDiv);
    }
  });
}

  </script>
</body>
</html>
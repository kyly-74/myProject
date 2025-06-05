<?php
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

// Kiểm tra thời gian hết hạn phiên (30 phút)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    $_SESSION['message'] = 'Phiên đăng nhập đã hết hạn!';
    header('Location: index.php');
    exit;
}
$_SESSION['last_activity'] = time();

// Tạo CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Xử lý hiển thị sidebar
$sidebar = isset($_GET['sidebar']) ? $_GET['sidebar'] : '';
$auth_tab = isset($_GET['tab']) ? $_GET['tab'] : 'login';
$show_user_info = isset($_GET['show']) && $_GET['show'] === 'user_info';
$show_change_password = isset($_GET['show']) && $_GET['show'] === 'change_password';

// Lấy thông tin người dùng nếu cần
$user_info = [];
if ($show_user_info && isset($_SESSION['user']['id'])) {
    $conn = new mysqli('localhost', 'root', '', 'trafficedu');
    if (!$conn->connect_error) {
        $userId = $_SESSION['user']['id'];
        $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $user_info = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();
    } else {
        $_SESSION['message'] = 'Lỗi tải thông tin người dùng';
    }
}

// Xử lý thông báo
$message = isset($_SESSION['message']) ? htmlspecialchars($_SESSION['message']) : '';
unset($_SESSION['message']); // Xóa thông báo ngay sau khi hiển thị
unset($_SESSION['message_time']); // Xóa thời gian thông báo
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <title>TrafficEdu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo">TrafficEdu</div>
            <ul class="nav-links">
                <li><a href="index.php" class="active">Trang chủ</a></li>
                <li><a href="lythuyet.html">Lý thuyết</a></li>
                <li><a href="../public/chonchucnang.php">Ôn tập</a></li>
                <li><a href="thithu.html">Thi thử</a></li>
                <li>
                    <?php if (isset($_SESSION['user']['name'])): ?>
                        <a href="?sidebar=logout" class="user-account">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user']['name']); ?>
                        </a>
                    <?php else: ?>
                        <a href="?sidebar=auth" class="auth-link">
                            <i class="fas fa-user"></i> Tài khoản
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>TrafficEdu – Học Lái Xe</h1>
            <p>Học lý thuyết, ôn tập và thi thử để chuẩn bị cho kỳ thi bằng lái xe.</p>
            <p>🚗 Chuẩn bị thi bằng lái với lý thuyết, ôn tập, thi thử.</p>
            <p>📚 Học quy định giao thông, luật lái xe.</p>
            <p>📝 Luyện tập câu hỏi thực tế, kiểm tra kiến thức.</p>
            <p>🚦 Truy cập ngay để bắt đầu hành trình lái xe của bạn!</p>
            <p>🚀 <strong>TrafficEdu</strong> - Nơi bạn bắt đầu hành trình lái xe an toàn!</p>
            <a href="#features" class="explore-btn">Khám phá</a>
        </div>
    </section>

    <section class="features" id="features">
        <h2 class="section-title">Tính Năng</h2>
        <div class="card-container">
            <a href="lythuyet.html" class="card card-link">
                <i class="fas fa-book" style="color: #10b981;"></i>
                <h3>Lý thuyết</h3>
                <p>Học quy định giao thông.</p>
            </a>
            <a href="../public/chonchucnang.php" class="card card-link">
                <i class="fas fa-clipboard" style="color: #e11d48;"></i>
                <h3>Ôn tập</h3>
                <p>Luyện câu hỏi thực tế.</p>
            </a>
            <a href="thithu.html" class="card card-link">
                <i class="fas fa-pen" style="color: #3b82f6;"></i>
                <h3>Thi thử</h3>
                <p>Kiểm tra kiến thức.</p>
            </a>
        </div>
    </section>

    <div class="auth-sidebar <?php echo $sidebar === 'auth' ? 'active' : ''; ?>">
        <a href="index.php" class="close-sidebar"><i class="fas fa-times"></i></a>
        <h2 class="sidebar-title">Tài khoản</h2>
        <?php if ($message && $sidebar === 'auth'): ?>
            <div class="auth-message"><?php echo $message; ?></div>
        <?php endif; ?>
        <div class="auth-tabs">
            <a href="?sidebar=auth&tab=login" class="tab-button <?php echo $auth_tab === 'login' ? 'active' : ''; ?>">Đăng nhập</a>
            <a href="?sidebar=auth&tab=register" class="tab-button <?php echo $auth_tab === 'register' ? 'active' : ''; ?>">Đăng ký</a>
        </div>
        <form action="auth.php" method="POST" class="auth-form <?php echo $auth_tab === 'login' ? 'active' : ''; ?>">
            <input type="hidden" name="action" value="login">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="input-group">
                <label for="loginUsername">Tên đăng nhập</label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="loginUsername" name="username" placeholder="Tên đăng nhập" required>
                </div>
            </div>
            <div class="input-group">
                <label for="loginPassword">Mật khẩu</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="loginPassword" name="password" placeholder="Mật khẩu" required>
                </div>
            </div>
            <button type="submit" class="submit-btn">Đăng nhập</button>
        </form>
        <form action="auth.php" method="POST" class="auth-form <?php echo $auth_tab === 'register' ? 'active' : ''; ?>">
            <input type="hidden" name="action" value="register">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="input-group">
                <label for="registerName">Họ và tên</label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="registerName" name="name" placeholder="Họ và tên" required>
                </div>
            </div>
            <div class="input-group">
                <label for="registerUsername">Tên đăng nhập</label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="registerUsername" name="username" placeholder="Tên đăng nhập" required>
                </div>
            </div>
            <div class="input-group">
                <label for="registerEmail">Email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="registerEmail" name="email" placeholder="Email" required>
                </div>
            </div>
            <div class="input-group">
                <label for="registerPassword">Mật khẩu</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="registerPassword" name="password" placeholder="Mật khẩu" required>
                </div>
            </div>
            <div class="input-group">
                <label for="confirmPassword">Nhập lại mật khẩu</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Nhập lại mật khẩu" required>
                </div>
            </div>
            <button type="submit" class="submit-btn">Đăng ký</button>
        </form>
    </div>

    <div class="logout-sidebar <?php echo $sidebar === 'logout' ? 'active' : ''; ?>">
        <a href="index.php" class="close-sidebar"><i class="fas fa-times"></i></a>
        <h2 class="sidebar-title">Tài khoản</h2>
        <div class="sidebar-buttons">
            <a href="?sidebar=logout&show=user_info" class="info-btn">Xem thông tin</a>
            <a href="?sidebar=logout&show=change_password" class="change-password-btn">Đổi mật khẩu</a>
        </div>
        <div class="user-info <?php echo $show_user_info ? 'show' : ''; ?>">
            <?php if ($show_user_info && $user_info): ?>
                <p><strong>Tên:</strong> <?php echo htmlspecialchars($user_info['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user_info['email']); ?></p>
            <?php elseif ($show_user_info): ?>
                <p>Không thể tải thông tin</p>
            <?php endif; ?>
        </div>
        <div class="change-password-section <?php echo $show_change_password ? 'show' : ''; ?>">
            <h3 class="sidebar-subtitle">Đổi mật khẩu</h3>
            <form action="change_password.php" method="POST" class="change-password-form">
                <input type="hidden" name="action" value="change_password">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="input-group">
                    <label for="currentPassword">Mật khẩu hiện tại</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="currentPassword" name="currentPassword" placeholder="Mật khẩu hiện tại" required>
                    </div>
                </div>
                <div class="input-group">
                    <label for="newPassword">Mật khẩu mới</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="newPassword" name="newPassword" placeholder="Mật khẩu mới" required>
                    </div>
                </div>
                <div class="input-group">
                    <label for="confirmNewPassword">Nhập lại mật khẩu mới</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" id="confirmNewPassword" name="confirmNewPassword" placeholder="Nhập lại mật khẩu mới" required>
                    </div>
                </div>
                <button type="submit" class="submit-btn">Đổi mật khẩu</button>
            </form>
        </div>
        <form action="logout.php" method="POST">
            <input type="hidden" name="action" value="logout">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="logout-btn">Đăng xuất</button>
        </form>
    </div>

    <div class="overlay <?php echo $sidebar === 'auth' || $sidebar === 'logout' ? 'active' : ''; ?>"></div>
    <?php if ($message): ?>
        <div class="toast-container">
            <div class="toast <?php echo strpos($message, 'thành công') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        </div>
    <?php endif; ?>

    <footer>
        <div class="footer-content">
            <p><strong>TrafficEdu</strong> - Học thi lái xe.</p>
            <p>© 2025 TrafficEdu.</p>
        </div>
    </footer>

    <!-- JavaScript để tự động ẩn thông báo sau 5 giây -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => {
                        toast.remove(); // Xóa hoàn toàn toast khỏi DOM
                    }, 500); // Chờ hiệu ứng opacity hoàn tất (0.5 giây)
                }, 3000); // 5 giây
            });
        });
    </script>
</body>
</html>
<?php
require 'db.php';

$dsn = "mysql:host=" . host . ";dbname=" . db . ";charset=" . charset;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, user, pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// 開啟 Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 驗證用戶是否已登入
function check_logged_in() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['session_token'])) {
        $_SESSION['error'] = '請先登入';
        header('Location: ../public/login.php');
        exit;
    }
}

// 用來清理過期的 session，防止用戶登錄其他裝置後，舊 session 還存在
function clean_expired_sessions($pdo) {
    $stmt = $pdo->prepare('DELETE FROM sessions WHERE expires_at < NOW()');
    $stmt->execute();
}

// 在每次頁面加載時呼叫
// clean_expired_sessions($pdo);

if (isset($_SESSION['error'])){ ?>
    <div class="container d-flex justify-content-center mt-5">
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert" style="max-width: 300px;">
            <strong>錯誤！</strong> <?php echo htmlspecialchars($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php }
if (isset($_SESSION['success'])){ ?>
    <div class="container d-flex justify-content-center mt-5">
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert" style="max-width: 300px;">
            <strong>成功！</strong> <?php echo htmlspecialchars($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php }?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
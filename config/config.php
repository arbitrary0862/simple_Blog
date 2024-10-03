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
    // 單一設備登入檢查
    checkSingleLogin();
}

// 單一設備登入檢查
function checkSingleLogin() {
    global $pdo;
    if (isset($_SESSION['user_id']) && isset($_SESSION['session_token'])) {
        // 查詢使用者的 session_token 是否一致
        $stmt = $pdo->prepare('SELECT Session_Token FROM users WHERE ID = :id');
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $user = $stmt->fetch();

        // 如果 session_token 不一致，導回登入頁
        if (!$user || $user['Session_Token'] !== $_SESSION['session_token']) {
            $_SESSION['error'] = '該使用者已在其他裝置登入';
            header('Location: login.php');
            exit();
        }
    } else {
        // 如果 session 不存在，導回登入頁
        $_SESSION['error'] = '請先登入';
        header('Location: login.php');
        exit();
    }
}

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
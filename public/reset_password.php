<?php
// config/config.php 包含資料庫連線和 session 設定
require __DIR__ . '/../config/config.php';

// 檢查是否從連結中獲取重置密碼的 token
if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    // 如果沒有 token，重定向到首頁
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>重置密碼</title>
    <?php include  __DIR__ . '/../src/includes/header.php'; ?>
</head>
<body>

<div class="container d-flex justify-content-center mt-5">
    <div class="col-md-6">
        <h2 class="text-center">重置密碼</h2>

        <!-- 顯示錯誤或成功訊息 -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php elseif (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                <?php echo htmlspecialchars($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- 重置密碼表單 -->
        <form action="../src/actions/reset_password_action.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
            <div class="mb-3">
                <label for="password" class="form-label">新密碼</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">確認新密碼</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">重置密碼</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

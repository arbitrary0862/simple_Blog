<?php require __DIR__ . '/../config/config.php'; // 資料庫連線設置
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>註冊</title>
    <?php include  __DIR__ . '/../src/includes/header.php'; ?>
</head>
<body>
<div class="container mt-5">
    <h2>會員註冊</h2>
    <form action="../src/actions/register_action.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">信箱</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">姓名</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">密碼</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">註冊</button>
    </form>
    <div class="mt-3 d-flex gap-2">
        <a href="login.php" class="btn btn-secondary">登入帳號</a>
        <a href="forgot_password.php" class="btn btn-info">忘記密碼</a>
    </div>
</div>
</body>
</body>
</html>

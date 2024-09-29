<?php require __DIR__ . '/../config/config.php'; // 資料庫連線設置?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>忘記密碼</title>
</head>
<body>
<div class="container mt-5">
    <h2>忘記密碼</h2>
    <form action="../src/actions/forgot_password_action.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">信箱</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">發送重設連結</button>
    </form>
    <div class="mt-3 d-flex gap-2">
        <a href="login.php" class="btn btn-secondary">登入帳號</a>
        <a href="register.php" class="btn btn-info">註冊帳號</a>
    </div>
</div>
</body>
</html>

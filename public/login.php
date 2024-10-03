<?php require __DIR__ . '/../config/config.php'; // 資料庫連線設置?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>登入</title>
    <script src="https://www.google.com/recaptcha/enterprise.js?render=<?php echo reCAPTCHA; ?>"></script>
</head>
<body>
<div class="container mt-5">
    <h2>會員登入</h2>
    <form id="loginForm" action="../src/actions/login_action.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">信箱</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">密碼</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <!-- 存放reCAPTCHA token -->
        <div class="g-recaptcha" data-sitekey="<?php echo reCAPTCHA; ?>"></div>
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
        <button type="submit" class="btn btn-primary">登入</button>
    </form>
    <div class="mt-3 d-flex gap-2">
        <a href="register.php" class="btn btn-secondary">註冊帳號</a>
        <a href="forgot_password.php" class="btn btn-info">忘記密碼</a>
        <a href="index.php" class="btn btn-primary">前往首頁</a>
    </div>
</div>
<script>
    // 等待DOM加載完成後執行
    document.addEventListener('DOMContentLoaded', function () {
        // 處理reCAPTCHA
        const form = document.getElementById('loginForm');
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // 防止表單立即提交
            grecaptcha.ready(async () => {
                // 獲取reCAPTCHA的token
                const token = await grecaptcha.execute('<?php echo reCAPTCHA; ?>', { action: 'LOGIN' });
                // 將token附加到隱藏的input中
                document.getElementById('g-recaptcha-response').value = token;
                // 提交表單
                form.submit();
            });
        });
    });
</script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

</body>
</html>

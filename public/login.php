<?php require __DIR__ . '/../config/config.php'; // 資料庫連線設置 ?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>登入</title>
    <?php include __DIR__ . '/../src/includes/header.php'; ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
        <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
        <button type="submit" class="btn btn-primary">登入</button>
    </form>
    <div class="mt-3 d-flex gap-2">
        <a href="register.php" class="btn btn-secondary">註冊帳號</a>
        <a href="forgot_password.php" class="btn btn-info">忘記密碼</a>
        <a href="index.php" class="btn btn-primary">前往首頁</a>
    </div>
</div>
<script>
    window.onload = function() {
        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo reCAPTCHA_V3_KEY; ?>', {action: 'login'}).then(function(token) {
                document.getElementById('recaptchaResponse').value = token;
            }).catch(function(error) {
                console.error('reCAPTCHA execution failed: ', error);
            });
        });
    };
</script>
</body>
</html>

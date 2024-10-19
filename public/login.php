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
        <?php
        // Line登入功能
        $line_login_url = 'https://access.line.me/oauth2/v2.1/authorize?response_type=code';
        $line_login_url .= '&client_id=' . urlencode(LINE_CHANNEL_ID); // LINE Channel ID
        $line_login_url .= '&redirect_uri=' . urlencode('https://127.0.0.1/simple_blog/src/actions/line_callback.php'); // 回調 URL
        $line_login_url .= '&state=' . urlencode(bin2hex(random_bytes(16))); // 防止 CSRF 攻擊的隨機數
        $line_login_url .= '&scope=profile%20openid%20email'; // 要求的權限
        
        // 生成登錄按鈕
        echo '<a href="' . $line_login_url . '" class="btn btn-success">使用 LINE 登入</a>';
        ?>
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

<?php
require __DIR__ . '/../../config/config.php'; // 資料庫連線設置

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 取得 reCAPTCHA 回應
    $secretKey = reCAPTCHA_V3_SERVER;  // Secret Key
    $recaptchaResponse = $_POST['recaptcha_response'];
    
    // 發送驗證請求至 Google reCAPTCHA 伺服器
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'secret' => $secretKey,
        'response' => $recaptchaResponse
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $verifyResponse = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($verifyResponse);

    // 檢查驗證結果
    if ($responseData->success && $responseData->score >= 0.5) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // 查詢會員資料
        $stmt = $pdo->prepare('SELECT id, password, session_token FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // 單一裝置登入：生成新的 session token，並更新DB的Token
            $newSessionToken = bin2hex(random_bytes(32));
            $stmt = $pdo->prepare('UPDATE users SET session_token = :session_token WHERE id = :id');
            $stmt->execute([':session_token' => $newSessionToken, ':id' => $user['id']]);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['session_token'] = $newSessionToken;
            header('Location: ../../public/dashboard.php');
        } else {
            $_SESSION['error'] = '信箱或密碼錯誤。';
            header('Location: ../../public/login.php');
        }
    } else {
        // 驗證失敗
        $_SESSION['error'] = 'reCAPTCHA 驗證失敗，錯誤原因：' . implode(', ', $responseData->{'error-codes'});
        header('Location: ../../public/login.php');
        exit;
    }
}

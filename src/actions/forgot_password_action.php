<?php
require __DIR__ . '/../../config/config.php'; // 資料庫連線設置

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // 查詢是否有這個信箱的用戶
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // 生成重設密碼的token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // 設定 token 過期時間 (例如1小時)

        // 更新 users 表中的 reset_token 和 reset_token_expiry
        $stmt = $pdo->prepare('UPDATE users SET reset_token = :token, reset_token_expiry = :expiry WHERE email = :email');
        $stmt->execute([
            ':token' => $token,
            ':expiry' => $expiry,
            ':email' => $email
        ]);

        // 發送重設連結到用戶信箱
        $resetLink = "http://127.0.0.1/simple_blog/public/reset_password.php?token=$token";
        // send_mail($email, "重設密碼連結", "點擊以下連結重設密碼: $resetLink");

        $_SESSION['success'] = '重設密碼連結已發送到您的信箱。'.$resetLink;
        header('Location: ../../public/login.php');
    } else {
        $_SESSION['error'] = '信箱未註冊。';
        header('Location: ../../public/forgot_password.php');
    }
}
?>
<?php
require __DIR__ . '/../../config/config.php'; // 資料庫連線設置

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
}

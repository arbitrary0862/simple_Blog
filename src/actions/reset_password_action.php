<?php
require __DIR__ . '/../../config/config.php'; // 資料庫連線設置

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
    } else {
        $_SESSION['error'] = '無效的重置連結';
        header('Location: index.php');
        exit();
    }

    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 確認密碼是否一致
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = '兩次密碼輸入不一致';
        header("Location: ../../public/reset_password.php?token=" . urlencode($token));
        exit();
    }

    // 驗證 token 是否存在並且尚未過期
    $stmt = $pdo->prepare('SELECT id, reset_token_expiry FROM users WHERE reset_token = :token');
    $stmt->execute([':token' => $token]);
    $user = $stmt->fetch();

    if ($user) {
        // 檢查 token 是否過期
        $current_time = date('Y-m-d H:i:s');
        if ($current_time > $user['reset_token_expiry']) {
            $_SESSION['error'] = '重置連結已過期，請重新請求';
            header('Location: ../../public/forgot_password.php');
            exit();
        }

        // 密碼驗證成功，更新密碼
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // 更新密碼並清除 token 和過期時間
        $stmt = $pdo->prepare('UPDATE users SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE id = :id');
        $stmt->execute([
            ':password' => $hashed_password,
            ':id' => $user['id']
        ]);

        $_SESSION['success'] = '密碼重置成功，請使用新密碼登入';
        header('Location: ../../public/login.php');
        exit();
    } else {
        $_SESSION['error'] = '無效的重置連結';
        header("Location: ../../public/reset_password.php?token=" . urlencode($token));
        exit();
    }
}
?>

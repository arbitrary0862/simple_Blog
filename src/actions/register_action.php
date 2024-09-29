<?php
require __DIR__ . '/../../config/config.php'; // 資料庫連線設置

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 接收表單資料
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 檢查信箱是否已經被使用
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = '此信箱已被註冊。';
        header('Location: ../../public/register.php');
        exit;
    }

    // 插入新會員資料
    $stmt = $pdo->prepare('INSERT INTO users (email, name, password) VALUES (:email, :name, :password)');
    if ($stmt->execute([':email' => $email, ':name' => $name, ':password' => $password])) {
        $_SESSION['success'] = '註冊成功，請登入。';
        header('Location: ../../public/login.php');
    } else {
        $_SESSION['error'] = '註冊失敗，請重試。';
        header('Location: ../../public/register.php');
    }
}

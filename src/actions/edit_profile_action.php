<?php
require __DIR__ . '/../../config/config.php'; // 資料庫連線設置
// 檢查會員是否已登入
check_logged_in();

// 處理會員資料更新請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 驗證密碼是否一致
    if (!empty($password) && $password !== $confirm_password) {
        $_SESSION['error'] = '密碼不一致';
        header('Location: edit_profile.php');
        exit();
    }

    // 更新會員資料
    try {
        if (!empty($password)) {
            // 如果有輸入密碼，則更新密碼
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id');
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashed_password,
                ':id' => $_SESSION['user_id']
            ]);
            $_SESSION['success'] = '資料更新成功，請重新登入';
            header('Location: ../../public/login.php');
            exit();
        } else {
            // 沒有輸入密碼，只更新姓名和電子郵件
            $stmt = $pdo->prepare('UPDATE users SET name = :name, email = :email WHERE id = :id');
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':id' => $_SESSION['user_id']
            ]);
            $_SESSION['success'] = '資料更新成功';
        }
        $_SESSION['success'] = '資料更新成功';
    } catch (PDOException $e) {
        $_SESSION['error'] = '資料更新失敗，請稍後再試';
    }

    header('Location: ../../public/edit_profile.php');
    exit();
}

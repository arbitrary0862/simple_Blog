<?php
require __DIR__ . '/../../config/config.php'; // 資料庫連線設置

// 檢查會員是否已登入
check_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $title = $_POST['title'];
    $alias = $_POST['alias'];
    $publishStart = $_POST['publish_start'];
    $publishEnd = $_POST['publish_end'];
    $isMemberOnly = isset($_POST['is_member_only']) ? 1 : 0;
    $content = $_POST['content'];

    // 檢查別名是否已存在
    $stmt = $pdo->prepare('SELECT id FROM articles WHERE alias = :alias');
    $stmt->execute([':alias' => $alias]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = '別名已存在，請使用其他名稱';
        header('Location: ../../public/new_article.php');
        exit;
    }

    // 插入文章資料
    $stmt = $pdo->prepare('INSERT INTO articles (user_id, title, alias, content, publish_start, publish_end, is_member_only) VALUES (:user_id, :title, :alias, :content, :publish_start, :publish_end, :is_member_only)');
    if ($stmt->execute([
        ':user_id' => $userId,
        ':title' => $title,
        ':alias' => $alias,
        ':content' => $content,
        ':publish_start' => $publishStart,
        ':publish_end' => $publishEnd,
        ':is_member_only' => $isMemberOnly
    ])) {
        $_SESSION['success'] = '文章新增成功';
        header('Location: ../../public/new_article.php');
    } else {
        $_SESSION['error'] = '新增文章失敗，請重試';
        header('Location: ../../public/new_article.php');
    }
}
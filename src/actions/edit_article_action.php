<?php
require __DIR__ . '/../../config/config.php'; // 資料庫連線設置

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $articleId = $_POST['ID'];
    $title = $_POST['Title'];
    $alias = $_POST['Alias'];
    $publishStart = $_POST['Publish_Start'];
    $publishEnd = $_POST['Publish_End'];
    $isMemberOnly = isset($_POST['Is_Member_Only']) ? 1 : 0;
    $content = $_POST['Content'];

    // 檢查別名是否已被其他文章使用
    $stmt = $pdo->prepare('SELECT ID FROM articles WHERE Alias = :Alias AND ID != :ID');
    $stmt->execute([':Alias' => $alias, ':ID' => $articleId]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = '別名已存在，請使用其他名稱。';
        header("Location: ../../public/edit_article.php?ID=$articleId");
        exit;
    }

    // 更新文章資料
    $stmt = $pdo->prepare('UPDATE Articles SET Title = :Title, Alias = :Alias, Content = :Content, Publish_Start = :Publish_Start, Publish_End = :Publish_End, Is_Member_Only = :Is_Member_Only WHERE ID = :ID AND User_ID = :User_ID');
    if ($stmt->execute([
        ':Title' => $title,
        ':Alias' => $alias,
        ':Content' => $content,
        ':Publish_Start' => $publishStart,
        ':Publish_End' => $publishEnd,
        ':Is_Member_Only' => $isMemberOnly,
        ':ID' => $articleId,
        ':User_ID' => $_SESSION['user_id']
    ])) {
        $_SESSION['success'] = '文章更新成功。';
        header("Location: ../../public/edit_article.php?ID=$articleId");
    } else {
        $_SESSION['error'] = '更新文章失敗，請重試。';
        header("Location: ../../public/edit_article.php?ID=$articleId");
    }
}
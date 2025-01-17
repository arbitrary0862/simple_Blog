<?php
require __DIR__ . '/../config/config.php';
// 檢查會員是否已登入
check_logged_in();

$sql = "SELECT * FROM users WHERE ID = :ID";
$stmt = $pdo->prepare($sql);
$stmt->execute([':ID' => $_SESSION['user_id']]);
$user = $stmt->fetch();
if(!$user){
    $_SESSION['error'] = '尚未登入系統';
    header('Location: ' . __DIR__ . '/../public/login.php');
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>會員儀表板</title>
    <?php include  __DIR__ . '/../src/includes/header.php'; ?>
</head>
<body>
<div class="container mt-5">
    <h2>歡迎, <?= htmlspecialchars($user['Name']) ?></h2>
    <p>歡迎來到會員儀表板，您可以在這裡管理您的文章。</p>

    <div class="mt-4">
        <a href="index.php" class="btn btn-primary">前往首頁</a>
        <a href="article_list.php" class="btn btn-warning">查看我的文章</a>
        <a href="new_article.php" class="btn btn-success">新增文章</a>
        <a href="edit_profile.php" class="btn btn-info">編輯個人資料</a>
        <a href="logout.php" class="btn btn-danger">登出</a>
    </div>
</div>
</body>
</html>

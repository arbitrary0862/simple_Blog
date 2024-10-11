<?php 
require __DIR__ . '/../config/config.php'; // 資料庫連線設置
// 檢查會員是否已登入
check_logged_in();
// 取得文章ID
$articleId = $_GET['ID'];
$stmt = $pdo->prepare('SELECT * FROM articles WHERE ID = :ID AND User_ID = :User_ID');
$stmt->execute([':ID' => $articleId, ':User_ID' => $_SESSION['user_id']]);
$article = $stmt->fetch();
if (!$article) {
    $_SESSION['error'] = '文章不存在或無權編輯。';
    header('Location: article_list.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>編輯文章</title>
    <?php include  __DIR__ . '/../src/includes/header.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script> <!-- 使用 TinyMCE 網頁編輯器 -->
    <script>
        tinymce.init({
            selector: '#Content',
            plugins: 'image code',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | link image | code', // 工具列選項
            images_upload_url: 'upload_image.php', // 圖片上傳處理 URL
            automatic_uploads: true, // 自動上傳圖片
            height: 500, // 設定編輯器高度
            document_base_url: '../',  // 指定相對於根目錄的基本 URL
        });
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>編輯文章</h2>
    <form action="../src/actions/edit_article_action.php" method="POST">
        <input type="hidden" name="ID" value="<?= $article['ID'] ?>">
        <div class="mb-3">
            <label for="Title" class="form-label">標題</label>
            <input type="text" class="form-control" id="Title" name="Title" value="<?= $article['Title'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="Alias" class="form-label">別名 (URL)</label>
            <input type="text" class="form-control" id="Alias" name="Alias" value="<?= $article['Alias'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="Publish_Start" class="form-label">發佈起始日</label>
            <input type="datetime-local" class="form-control" id="Publish_Start" name="Publish_Start" value="<?= $article['Publish_Start'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="Publish_End" class="form-label">發佈結束日</label>
            <input type="datetime-local" class="form-control" id="Publish_End" name="Publish_End" value="<?= $article['Publish_End'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="Is_Member_Only" class="form-label">限制會員觀看</label>
            <input type="checkbox" id="Is_Member_Only" name="Is_Member_Only" <?= $article['Is_Member_Only'] ? 'checked' : '' ?>>
        </div>
        <div class="mb-3">
            <label for="Content" class="form-label">內容</label>
            <textarea id="Content" name="Content" class="form-control"><?= $article['Content'] ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">儲存變更</button>
    </form>
    <a href="dashboard.php" class="btn btn-primary mt-1">前往 Dashboard</a>
</div>
</body>
</html>
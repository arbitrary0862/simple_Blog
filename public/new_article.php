<?php require __DIR__ . '/../config/config.php'; // 資料庫連線設置
    // 檢查會員是否已登入
    check_logged_in();
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>新增文章</title>
    <?php include  __DIR__ . '/../src/includes/header.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script> <!-- 網頁編輯器 -->
    <script>
        tinymce.init({
            selector: '#Content',
            plugins: 'image code',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | link image | code', // 工具列選項
            images_upload_url: 'upload_image.php', // 圖片上傳處理
            automatic_uploads: true,
            height: 500, // 設定編輯器高度
            document_base_url: '../',  // 指定相對於根目錄的基本 URL
        });
    </script>
</head>
<body>
<div class="container mt-5">
    <h2>新增文章</h2>
    <form action="../src/actions/new_article_action.php" method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">標題</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="alias" class="form-label">別名 (URL)</label>
            <input type="text" class="form-control" id="alias" name="alias" required>
        </div>
        <div class="mb-3">
            <label for="publish_start" class="form-label">發佈起始日</label>
            <input type="datetime-local" class="form-control" id="publish_start" name="publish_start" required>
        </div>
        <div class="mb-3">
            <label for="publish_end" class="form-label">發佈結束日</label>
            <input type="datetime-local" class="form-control" id="publish_end" name="publish_end" required>
        </div>
        <div class="mb-3">
            <label for="is_member_only" class="form-label">限制會員觀看</label>
            <input type="checkbox" id="is_member_only" name="is_member_only">
        </div>
        <div class="mb-3">
            <label for="Content" class="form-label">內容</label>
            <textarea id="Content" name="Content" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">新增文章</button>
    </form>
    <a href="dashboard.php" class="btn btn-primary mt-1">前往 Dashboard</a>
</div>
</body>
</html>
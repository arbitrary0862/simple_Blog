<?php
require __DIR__ . '/../config/config.php';

// 獲取當前頁碼
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10; // 每頁顯示10筆
$offset = ($page - 1) * $perPage;

// 獲取搜索關鍵字
$search = isset($_GET['search']) ? $_GET['search'] : '';

// 準備SQL查詢：根據是否登入顯示會員限定的文章
$sql = "SELECT id, title, publish_start, publish_end, is_member_only 
        FROM articles 
        WHERE publish_start <= CURRENT_DATE() AND publish_end >= CURRENT_DATE()";

$params = [];

if ($search) {
    $sql .= " AND title LIKE :search";
    $params[':search'] = "%$search%";
}

// 如果未登入，只顯示非會員限定的文章
if (!isset($_SESSION['user_id'])) {
    $sql .= " AND is_member_only = 0";
}

$sql .= " ORDER BY publish_start DESC LIMIT :offset, :perPage";

// 執行查詢
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$articles = $stmt->fetchAll();
$stmt->closeCursor();

// 獲取總文章數
$countSql = "SELECT COUNT(*) 
             FROM articles 
             WHERE publish_start <= CURRENT_DATE() AND publish_end >= CURRENT_DATE()";
if ($search) {
    $countSql .= " AND title LIKE :search";
}
if (!isset($_SESSION['user_id'])) {
    $countSql .= " AND is_member_only = 0";
}
$countStmt = $pdo->prepare($countSql);
if ($search) {
    $countStmt->bindValue(':search', "%$search%");
}
$countStmt->execute();
$totalArticles = $countStmt->fetchColumn();
$totalPages = ceil($totalArticles / $perPage);

?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>簡易部落格首頁</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">文章列表</h1>

    <!-- 搜索表單 -->
    <form action="" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="搜索文章標題" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">搜索</button>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>標題</th>
                <th>發佈開始日期</th>
                <th>發佈結束日期</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($articles) > 0): ?>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td>
                            <a href="article.php?id=<?= htmlspecialchars($article['id']) ?>">
                                <?= htmlspecialchars($article['title']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($article['publish_start']) ?></td>
                        <td><?= htmlspecialchars($article['publish_end']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">無符合條件的文章</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- 分頁導航 -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <a href="dashboard.php" class="btn btn-primary mt-3">前往 Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
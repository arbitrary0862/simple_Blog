<?php
require_once __DIR__ . '/../config/config.php'; // 資料庫連線設置

// 獲取當前頁碼
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10; // 每頁顯示10筆
$offset = ($page - 1) * $perPage;

// 獲取搜索關鍵字
$search = isset($_GET['search']) ? $_GET['search'] : '';

// 準備SQL查詢
$sql = "SELECT id, title, publish_start, publish_end, is_member_only FROM articles 
        WHERE publish_start <= CURRENT_DATE() AND publish_end >= CURRENT_DATE()";

$params = [];

if ($search) {
    $sql .= " AND title LIKE :search";
    $params[':search'] = "%$search%";
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

// 獲取總文章數
$countSql = "SELECT COUNT(*) FROM articles WHERE publish_start <= CURRENT_DATE() AND publish_end >= CURRENT_DATE()";
if ($search) {
    $countSql .= " AND title LIKE :search";
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
    <title>文章列表</title>
</head>
<body>
    <div class="container mt-5">
        <h2>文章列表</h2>
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
                <?php foreach ($articles as $article): ?>
                    <?php if (!$article['is_member_only'] || isset($_SESSION['user_id'])): ?>
                        <tr>
                            <td><?= htmlspecialchars($article['title']) ?></td>
                            <td><?= $article['publish_start'] ?></td>
                            <td><?= $article['publish_end'] ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- 分頁 -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>">
                            <?= $i ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
        <a href="dashboard.php" class="btn btn-primary">前往 Dashboard</a>
    </div>
</body>
</html>
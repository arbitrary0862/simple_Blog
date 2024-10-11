<?php
require __DIR__ . '/../config/config.php'; // 資料庫連線設置

// 檢查會員是否已登入
check_logged_in();

$userId = $_SESSION['user_id']; // 取得當前會員ID

// 獲取當前頁碼
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10; // 每頁顯示10筆
$offset = ($page - 1) * $perPage;

// 獲取搜索關鍵字
$search = isset($_GET['search']) ? $_GET['search'] : '';

// 準備SQL查詢，只顯示該會員的文章
$sql = "SELECT ID, Title, Publish_Start, Publish_End FROM articles 
        WHERE User_ID = :User_ID";

$params = [':User_ID' => $userId];

if ($search) {
    $sql .= " AND Title LIKE :search";
    $params[':search'] = "%$search%";
}

$sql .= " ORDER BY Publish_Start DESC LIMIT :offset, :perPage";

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
$countSql = "SELECT COUNT(*) FROM articles WHERE User_ID = :User_ID";
if ($search) {
    $countSql .= " AND Title LIKE :search";
}
$countStmt = $pdo->prepare($countSql);
$countStmt->bindValue(':User_ID', $userId);
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
    <title>我的文章列表</title>
    <?php include  __DIR__ . '/../src/includes/header.php'; ?>
</head>
<body>
    <div class="container mt-5">
        <h2>我的文章列表</h2>
        <form action="" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="搜索文章標題" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">搜索</button>
            </div>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>編輯</th>
                    <th>標題</th>
                    <th>發佈開始日期</th>
                    <th>發佈結束日期</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <!-- 編輯按鈕放在第一欄，並使用 Bootstrap 的排版來確保按鈕高度與其他欄一致 -->
                        <td class="align-middle">
                            <a href="edit_article.php?ID=<?= $article['ID'] ?>" class="btn btn-warning btn-sm">編輯</a>
                        </td>
                        <td class="align-middle"><?= htmlspecialchars($article['Title']) ?></td>
                        <td class="align-middle"><?= $article['Publish_Start'] ?></td>
                        <td class="align-middle"><?= $article['Publish_End'] ?></td>
                    </tr>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
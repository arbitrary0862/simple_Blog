<?php
require __DIR__ . '/../../config/config.php'; // 資料庫連線設置

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id'])) {
        echo json_encode(['error' => '缺少文章 ID'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $articleId = $_POST['id'];
    $userId = $_SESSION['user_id'] ?? null;
    $sessionId = session_id();

    // 檢查是否為會員，並確保會員同一天只計算一次
    if ($userId) {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM Article_Views 
                                      WHERE Article_ID = :article_id 
                                      AND User_ID = :user_id 
                                      AND View_Date = CURRENT_DATE()');
        $stmt->execute([':article_id' => $articleId, ':user_id' => $userId]);
        $viewCount = $stmt->fetchColumn();
    } else {
        // 非會員用 Session_ID 記錄
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM Article_Views 
                                      WHERE Article_ID = :article_id 
                                      AND Session_ID = :session_id 
                                      AND View_Date = CURRENT_DATE()');
        $stmt->execute([':article_id' => $articleId, ':session_id' => $sessionId]);
        $viewCount = $stmt->fetchColumn();
    }

    if ($viewCount == 0) {
        // 插入新的觀看次數記錄
        $stmt = $pdo->prepare('INSERT INTO Article_Views (Article_ID, User_ID, Session_ID, View_Date) 
                                             VALUES (:article_id, :user_id, :session_id, CURRENT_DATE())');
        $stmt->execute([
            ':article_id' => $articleId,
            ':user_id' => $userId,
            ':session_id' => $sessionId
        ]);

        // 更新 articles 表中的 View_Count 欄位
        $stmt = $pdo->prepare('UPDATE articles SET View_Count = View_Count + 1 WHERE ID = :article_id');
        $stmt->execute([':article_id' => $articleId]);

        echo json_encode(['success' => '觀看次數已更新'], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['info' => '今日已計算過觀看次數'], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(['error' => '無效的請求方式'], JSON_UNESCAPED_UNICODE);
}
exit;
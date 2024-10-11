<?php
require __DIR__ . '/../config/config.php'; // 資料庫連線設置
header('Content-Type: application/json');

// 確認是否有上傳文件
if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    
    // 移除文件名中的特殊字符以避免路徑問題
    $fileName = preg_replace("/[^A-Z0-9._-]/i", "_", $fileName);

    // 設定上傳的目錄
    $uploadDir = __DIR__ . '/../images/'; // 確保該目錄存在
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // 自動創建目錄，設置寫入權限
    }
    
    $dest_path = $uploadDir . $fileName;

    // 移動上傳的文件到指定位置
    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        echo json_encode(['location' => '/images/' . $fileName]); // 返回相對路徑供前端使用
    } else {
        echo json_encode(['error' => 'File upload failed.']);
    }
} else {
    echo json_encode(['error' => 'No file uploaded or upload error.']);
}

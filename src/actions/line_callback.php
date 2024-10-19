<?php
require __DIR__ . '/../../config/config.php'; // 資料庫連線設置

// LINE API 認證後的 callback 處理
if (isset($_GET['code'])) {
    // LINE Login 授權碼
    $code = $_GET['code'];

    // 建立 POST 請求以獲取 Access Token
    $url = 'https://api.line.me/oauth2/v2.1/token';
    $data = [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => 'https://127.0.0.1/simple_blog/src/actions/line_callback.php',
        'client_id' => LINE_CHANNEL_ID,
        'client_secret' => LINE_CHANNEL_SECRET,
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $response = json_decode($result, true);

    if (isset($response['access_token'])) {
        // 使用 Access Token 取得用戶資料
        $accessToken = $response['access_token'];
        $userInfoUrl = 'https://api.line.me/v2/profile';
        $opts = [
            'http' => [
                'header' => "Authorization: Bearer " . $accessToken,
            ],
        ];

        $context = stream_context_create($opts);
        $userInfoResult = file_get_contents($userInfoUrl, false, $context);
        $userInfo = json_decode($userInfoResult, true);
        
        if (isset($userInfo['userId'])) {
            // 獲取的用戶資料
            $lineUserId = $userInfo['userId'];
            $displayName = isset($userInfo['displayName']) ? $userInfo['displayName'] : '';

            // 查詢用戶是否已存在
            $stmt = $pdo->prepare('SELECT ID FROM users WHERE Line_User_Id = :line_user_id');
            $stmt->execute([':line_user_id' => $lineUserId]);
            $user = $stmt->fetch();

            if ($user) {
                // 用戶已存在，更新 session 並登入
                $newSessionToken = bin2hex(random_bytes(32));
                $stmt = $pdo->prepare('UPDATE users SET Session_Token = :session_token WHERE ID = :id');
                $stmt->execute([':session_token' => $newSessionToken, ':id' => $user['ID']]);
                $_SESSION['user_id'] = $user['ID'];
                $_SESSION['session_token'] = $newSessionToken;
                $_SESSION['success'] = '登入成功';
                header('Location: ../../public/dashboard.php');
                exit;
            } else {
                // 用戶不存在，建立新會員
                $newSessionToken = bin2hex(random_bytes(32));
                $stmt = $pdo->prepare('INSERT INTO users (`Line_User_Id`, `Name`, `Session_Token`)
                                              VALUES (:line_user_id, :display_name, :session_token)');
                $stmt->execute([
                    ':line_user_id'     => $lineUserId,
                    ':display_name'     => $displayName,
                    ':session_token'    => $newSessionToken
                ]);

                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['session_token'] = $newSessionToken;
                $_SESSION['success'] = '註冊成功，請先完善會員資料';
                header('Location: ../../public/edit_profile.php');
                exit;
            }
        } else {
            // 用戶資料獲取失敗
            $_SESSION['error'] = '無法取得 LINE 使用者資料';
            header('Location: ../../public/login.php');
            exit;
        }
    } else {
        // Access Token 獲取失敗
        $_SESSION['error'] = 'LINE 授權失敗';
        header('Location: ../../public/login.php');
        exit;
    }
} else {
    // 未取得授權碼
    $_SESSION['error'] = 'LINE 授權流程錯誤';
    header('Location: ../../public/login.php');
    exit;
}

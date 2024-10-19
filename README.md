# simple_blog

# 1. 資料庫結構

```sql
CREATE DATABASE simple_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE simple_blog;

CREATE TABLE users (
    ID INT AUTO_INCREMENT PRIMARY KEY COMMENT '會員ID',
    Line_User_Id VARCHAR(255) NULL COMMENT 'LINE 用戶ID',
    Email VARCHAR(255) NOT NULL UNIQUE COMMENT '會員電子郵件',
    Password VARCHAR(255) NOT NULL COMMENT '會員密碼',
    Name VARCHAR(100) NOT NULL COMMENT '會員姓名',
    Session_Token VARCHAR(255) NULL COMMENT 'Session Token',
    Reset_Token VARCHAR(64) NULL COMMENT '重置密碼的 Token',
    Reset_Token_Expiry DATETIME NULL COMMENT 'Token 過期時間',
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改時間'
) COMMENT='會員資料表';

CREATE TABLE articles (
    ID INT AUTO_INCREMENT PRIMARY KEY COMMENT '文章ID',
    User_ID INT COMMENT '發佈會員ID',
    Title VARCHAR(255) NOT NULL COMMENT '文章標題',
    Alias VARCHAR(255) UNIQUE NOT NULL COMMENT '文章別名 (用於網址識別)',
    Content TEXT NOT NULL COMMENT '文章內容',
    Publish_Start DATETIME COMMENT '發佈開始日期',
    Publish_End DATETIME COMMENT '發佈結束日期',
    Is_Member_Only BOOLEAN DEFAULT FALSE COMMENT '是否限制會員觀看',
    View_Count INT DEFAULT 0 COMMENT '觀看次數',
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
    Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新時間'
) COMMENT='文章資料表';

CREATE TABLE article_views (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Article_ID INT NOT NULL,
    User_ID INT COMMENT '使用者ID',
    Session_ID VARCHAR(255) NULL COMMENT 'Session ID',
    View_Date DATE NOT NULL COMMENT '查看時間'
) COMMENT='使用者Session資料表';

CREATE TABLE sessions (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Session_ID VARCHAR(255) NOT NULL UNIQUE,
    User_ID INT NOT NULL,
    IP_Address VARCHAR(45) NOT NULL,
    User_Agent TEXT NOT NULL,
    Expires_At DATETIME NOT NULL,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

# 2.頁面結構說明

- **首頁**：顯示文章列表，包含搜尋功能和分頁。
- **註冊頁**：用戶註冊表單。
- **登入頁**：用戶登入表單，整合 Google reCAPTCHA v3。
- **會員編輯頁**：修改用戶的密碼和姓名。
- **會員文章列表頁**：顯示用戶所創建的文章。
- **新增/編輯文章頁**：用於創建或編輯文章，包含富文本編輯器。
- **文章詳細頁**：顯示文章內容和觀看次數。
- **忘記密碼頁**：用戶重設密碼。


# 3.預計結構

## config/ # 資料庫相關設定
- `config.php`  # 資料庫連線
- `db.php`      # 全局宣告設定

## public/  # 公共目錄 (網站根目錄)
- css/
  - `styles.css`           # 自定義樣式
- js/
  - `scripts.js`          # 自定義 JavaScript
- images/                 # 圖片存放目錄
- `index.php`             # 首頁 (文章列表)
- `login.php`             # 登入頁面
- `register.php`          # 註冊頁面
- `forgot_password.php`   # 忘記密碼頁面
- `dashboard.php`         # 儀表板
- `new_article.php`       # 新增文章頁面
- `edit_article.php`      # 編輯文章頁面
- `article_list.php`      # 會員文章列表頁面
- `view_article.php`      # 文章詳細頁面
- `edit_profile.php`      # 編輯會員資料頁面
- `logout.php`            # 登出功能
- `upload_image.php`      # 圖片上傳處理

## src/  # PHP後端邏輯
- actions/
  - `login_action.php`                # 登入處理
  - `register_action.php`             # 註冊處理
  - `forgot_password_action.php`      # 忘記密碼處理
  - `new_article_action.php`          # 新增文章處理
  - `edit_article_action.php`         # 編輯文章處理
  - `edit_profile_action.php`         # 更新會員資料處理
  - `logout_action.php`               # 登出處理
  - `increment_view_count.php`        # 觀看次數處理
- includes/
  - `header.php`                      # 頁面通用header
  - `footer.php`                      # 頁面通用footer
- utils/
  - `session_management.php`          # 管理登入狀態與session
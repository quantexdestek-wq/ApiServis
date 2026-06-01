<?php
session_start();

// InfinityFree veritabanı bilgilerin - BUNLARI KENDİ BİLGİLERİNLE DEĞİŞTİR!
define('DB_HOST', 'sql123.infinityfree.com');
define('DB_NAME', 'if0_12345678_apiportali');     // Veritabanı adın
define('DB_USER', 'if0_12345678');                // Veritabanı kullanıcı adın
define('DB_PASS', '8NebdZafvWowE');              // Veritabanı şifren

// Site ayarları
define('SITE_URL', 'http://apiportali.kesug.com');
define('SITE_NAME', 'API Portalı');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

function addApi($endpoint, $url, $description, $method = 'GET', $category = '') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO apis (endpoint, url, description, method, category) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$endpoint, $url, $description, $method, $category]);
    } catch (PDOException $e) {
        return false;
    }
}

function getApis($filter = 'all', $search = '') {
    global $pdo;
    $sql = "SELECT * FROM apis WHERE is_active = 1";
    $params = [];
    
    if ($search) {
        $sql .= " AND (endpoint LIKE ? OR url LIKE ? OR description LIKE ? OR category LIKE ?)";
        $searchTerm = "%$search%";
        $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
    }
    
    if ($filter === 'today') {
        $sql .= " AND DATE(created_at) = CURDATE()";
    } elseif ($filter === 'week') {
        $sql .= " AND created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
    }
    
    $sql .= " ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function deleteApi($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM apis WHERE id = ?");
    return $stmt->execute([$id]);
}

function updateApi($id, $endpoint, $url, $description, $method, $category) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE apis SET endpoint = ?, url = ?, description = ?, method = ?, category = ? WHERE id = ?");
        return $stmt->execute([$endpoint, $url, $description, $method, $category, $id]);
    } catch (PDOException $e) {
        return false;
    }
}

function incrementClick($id) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE apis SET click_count = click_count + 1 WHERE id = ?");
    return $stmt->execute([$id]);
}

function getStats() {
    global $pdo;
    return [
        'total' => $pdo->query("SELECT COUNT(*) FROM apis WHERE is_active = 1")->fetchColumn(),
        'today' => $pdo->query("SELECT COUNT(*) FROM apis WHERE is_active = 1 AND DATE(created_at) = CURDATE()")->fetchColumn(),
        'clicks' => $pdo->query("SELECT SUM(click_count) FROM apis WHERE is_active = 1")->fetchColumn() ?: 0
    ];
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function adminLogin($username, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        
        $stmt = $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$admin['id']]);
        
        return true;
    }
    return false;
}

function getApiByEndpoint($endpoint) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM apis WHERE endpoint = ? AND is_active = 1");
    $stmt->execute([$endpoint]);
    return $stmt->fetch();
}
?>
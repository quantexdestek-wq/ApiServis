<?php
require_once 'config.php';

// API endpoint olarak gelirse
if (isset($_GET['endpoint'])) {
    $endpoint = $_GET['endpoint'];
    $api = getApiByEndpoint($endpoint);
    
    if ($api) {
        incrementClick($api['id']);
        
        if (isset($_GET['redirect'])) {
            header("Location: " . $api['url']);
            exit;
        }
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'api' => [
                'endpoint' => $api['endpoint'],
                'url' => $api['url'],
                'description' => $api['description'],
                'method' => $api['method'],
                'category' => $api['category']
            ]
        ]);
        exit;
    } else {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'API bulunamadı']);
        exit;
    }
}

// AJAX istekleri
header('Content-Type: application/json; charset=utf-8');
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['ajax']) ? $_GET['ajax'] : '');

switch ($action) {
    case 'login':
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        if (adminLogin($username, $password)) {
            echo json_encode(['success' => true, 'message' => 'Giriş başarılı']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Hatalı giriş']);
        }
        break;
        
    case 'logout':
        session_destroy();
        echo json_encode(['success' => true]);
        break;
        
    case 'add_api':
        if (!isAdminLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Yetkisiz']);
            break;
        }
        
        $endpoint = isset($_POST['endpoint']) ? trim($_POST['endpoint']) : '';
        $url = isset($_POST['url']) ? trim($_POST['url']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $method = isset($_POST['method']) ? $_POST['method'] : 'GET';
        $category = isset($_POST['category']) ? trim($_POST['category']) : '';
        
        if (empty($endpoint) || empty($url)) {
            echo json_encode(['success' => false, 'message' => 'Endpoint ve URL gerekli']);
            break;
        }
        
        $endpoint = preg_replace('/[^a-zA-Z0-9-_]/', '', $endpoint);
        
        if (addApi($endpoint, $url, $description, $method, $category)) {
            echo json_encode(['success' => true, 'message' => 'API eklendi']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Bu endpoint zaten var']);
        }
        break;
        
    case 'delete_api':
        if (!isAdminLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Yetkisiz']);
            break;
        }
        
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if (deleteApi($id)) {
            echo json_encode(['success' => true, 'message' => 'API silindi']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Silme başarısız']);
        }
        break;
        
    case 'update_api':
        if (!isAdminLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Yetkisiz']);
            break;
        }
        
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $endpoint = isset($_POST['endpoint']) ? trim($_POST['endpoint']) : '';
        $url = isset($_POST['url']) ? trim($_POST['url']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $method = isset($_POST['method']) ? $_POST['method'] : 'GET';
        $category = isset($_POST['category']) ? trim($_POST['category']) : '';
        
        if (updateApi($id, $endpoint, $url, $description, $method, $category)) {
            echo json_encode(['success' => true, 'message' => 'API güncellendi']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Güncelleme başarısız']);
        }
        break;
        
    case 'get_apis':
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $apis = getApis($filter, $search);
        echo json_encode(['success' => true, 'apis' => $apis]);
        break;
        
    case 'get_stats':
        $stats = getStats();
        echo json_encode(['success' => true, 'stats' => $stats]);
        break;
        
    case 'track_click':
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($id > 0) {
            incrementClick($id);
        }
        echo json_encode(['success' => true]);
        break;
        
    case 'check_auth':
        echo json_encode(['authenticated' => isAdminLoggedIn()]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Geçersiz işlem']);
}
?>
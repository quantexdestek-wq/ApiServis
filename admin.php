<?php
require_once 'config.php';

// Giriş işlemi
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (adminLogin($username, $password)) {
        header('Location: /admin');
        exit;
    } else {
        $error = 'Kullanıcı adı veya şifre hatalı!';
    }
}

// Çıkış işlemi
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: /admin');
    exit;
}

// Admin girişi yapılmamışsa
if (!isAdminLoggedIn()):
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi - API Portalı</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh; display: flex;
            justify-content: center; align-items: center;
        }
        .login-box {
            background: white; border-radius: 20px; padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%; max-width: 400px;
        }
        .login-box h2 { text-align: center; margin-bottom: 30px; color: #333; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; margin-bottom: 8px; color: #333; font-weight: 600; }
        .input-group input {
            width: 100%; padding: 12px 15px;
            border: 2px solid #e0e0e0; border-radius: 10px;
            font-size: 1em;
        }
        .input-group input:focus { outline: none; border-color: #667eea; }
        .btn {
            width: 100%; padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; border: none; border-radius: 10px;
            font-size: 1em; font-weight: 600; cursor: pointer;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(102,126,234,0.4); }
        .error { background: #ffeaa7; color: #d63031; padding: 10px; border-radius: 8px; margin-top: 15px; text-align: center; }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #667eea; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2><i class="fas fa-lock"></i> Admin Girişi</h2>
        <form method="POST">
            <input type="hidden" name="action" value="login">
            <div class="input-group">
                <label>Kullanıcı Adı</label>
                <input type="text" name="username" placeholder="admin" required>
            </div>
            <div class="input-group">
                <label>Şifre</label>
                <input type="password" name="password" placeholder="••••••" required>
            </div>
            <button type="submit" class="btn">
                <i class="fas fa-sign-in-alt"></i> Giriş Yap
            </button>
            <?php if (isset($error)): ?>
            <div class="error"><i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?></div>
            <?php endif; ?>
        </form>
        <div class="back-link">
            <a href="/"><i class="fas fa-arrow-left"></i> Ana Sayfaya Dön</a>
        </div>
    </div>
</body>
</html>
<?php else: ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - API Portalı</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh; padding: 20px;
        }
        .container {
            max-width: 800px; margin: 0 auto;
            background: white; border-radius: 20px;
            padding: 40px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .header {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 30px;
        }
        .header h2 { color: #667eea; font-size: 1.8em; }
        .btn {
            padding: 10px 20px; border: none; border-radius: 10px;
            font-size: 0.9em; cursor: pointer; font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; width: 100%;
        }
        .btn-primary:hover { transform: translateY(-2px); }
        .btn-danger { background: #d63031; color: white; }
        .btn-danger:hover { background: #c0392b; }
        .btn-outline { background: transparent; border: 2px solid #667eea; color: #667eea; }
        .btn-outline:hover { background: #667eea; color: white; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: 600; color: #333; }
        .input-group input, .input-group select {
            width: 100%; padding: 10px 15px;
            border: 2px solid #e0e0e0; border-radius: 8px;
            font-size: 1em;
        }
        .input-group input:focus, .input-group select:focus { outline: none; border-color: #667eea; }
        .api-list { margin-top: 30px; }
        .api-item {
            background: #f8f9fa; padding: 15px; border-radius: 10px;
            margin-bottom: 10px; display: flex;
            justify-content: space-between; align-items: center;
        }
        .api-info small { color: #999; display: block; margin-top: 5px; }
        .toast-container { position: fixed; top: 30px; right: 30px; z-index: 10000; }
        .toast {
            background: white; padding: 15px 25px; border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3); margin-bottom: 10px;
            animation: slideIn 0.4s ease; border-left: 5px solid #00b894;
        }
        @keyframes slideIn {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #667eea; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="toast-container" id="toastContainer"></div>
    
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-crown"></i> Admin Panel</h2>
            <a href="?logout" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Çıkış</a>
        </div>

        <div class="input-group">
            <label>Endpoint (örn: selam)</label>
            <input type="text" id="endpoint" placeholder="selam">
        </div>
        <div class="input-group">
            <label>API URL</label>
            <input type="url" id="url" placeholder="https://api.telegram.org/bot...">
        </div>
        <div class="input-group">
            <label>Açıklama (Opsiyonel)</label>
            <input type="text" id="description" placeholder="API açıklaması">
        </div>
        <div class="input-group">
            <label>Method</label>
            <select id="method">
                <option value="GET">GET</option>
                <option value="POST">POST</option>
                <option value="PUT">PUT</option>
                <option value="DELETE">DELETE</option>
            </select>
        </div>
        <div class="input-group">
            <label>Kategori (Opsiyonel)</label>
            <input type="text" id="category" placeholder="Telegram, Test, vs.">
        </div>

        <button class="btn btn-primary" onclick="addApi()">
            <i class="fas fa-plus-circle"></i> API Ekle
        </button>

        <div class="api-list">
            <h3 style="margin-bottom: 15px;"><i class="fas fa-list"></i> Eklenen API'ler</h3>
            <div id="apiListContainer">Yükleniyor...</div>
        </div>

        <div class="back-link">
            <a href="/"><i class="fas fa-arrow-left"></i> Ana Sayfaya Dön</a>
        </div>
    </div>

    <script>
        function loadApis() {
            fetch('api.php?ajax=get_apis')
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.success) {
                        var html = '';
                        for (var i = 0; i < data.apis.length; i++) {
                            var api = data.apis[i];
                            html += '<div class="api-item">';
                            html += '<div class="api-info">';
                            html += '<strong>/api/' + api.endpoint + '</strong> - ' + api.method;
                            if (api.description) html += '<br>' + api.description;
                            html += '<small>' + api.click_count + ' istek • ' + api.created_at + '</small>';
                            html += '</div>';
                            html += '<div>';
                            html += '<button class="btn btn-outline" onclick="editApi(' + api.id + ', \'' + api.endpoint + '\', \'' + api.url.replace(/'/g, "\\'") + '\', \'' + (api.description || '').replace(/'/g, "\\'") + '\', \'' + api.method + '\', \'' + (api.category || '').replace(/'/g, "\\'") + '\')" style="margin-right:5px;"><i class="fas fa-edit"></i></button>';
                            html += '<button class="btn btn-danger" onclick="deleteApi(' + api.id + ')"><i class="fas fa-trash"></i></button>';
                            html += '</div>';
                            html += '</div>';
                        }
                        if (data.apis.length === 0) html = '<p style="text-align:center;color:#999;">Henüz API eklenmemiş</p>';
                        document.getElementById('apiListContainer').innerHTML = html;
                    }
                });
        }

        function addApi() {
            var endpoint = document.getElementById('endpoint').value.trim();
            var url = document.getElementById('url').value.trim();
            var description = document.getElementById('description').value.trim();
            var method = document.getElementById('method').value;
            var category = document.getElementById('category').value.trim();

            if (!endpoint || !url) {
                showToast('Endpoint ve URL gerekli!', 'error');
                return;
            }

            var formData = 'action=add_api&endpoint=' + encodeURIComponent(endpoint) +
                '&url=' + encodeURIComponent(url) +
                '&description=' + encodeURIComponent(description) +
                '&method=' + method +
                '&category=' + encodeURIComponent(category);

            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    document.getElementById('endpoint').value = '';
                    document.getElementById('url').value = '';
                    document.getElementById('description').value = '';
                    document.getElementById('category').value = '';
                    loadApis();
                    showToast('API eklendi! 🎉');
                } else {
                    showToast(data.message, 'error');
                }
            });
        }

        function deleteApi(id) {
            if (!confirm('Bu API\'yi silmek istediğinize emin misiniz?')) return;
            
            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=delete_api&id=' + id
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    loadApis();
                    showToast('API silindi!');
                }
            });
        }

        function editApi(id, endpoint, url, description, method, category) {
            document.getElementById('endpoint').value = endpoint;
            document.getElementById('url').value = url;
            document.getElementById('description').value = description;
            document.getElementById('method').value = method;
            document.getElementById('category').value = category;
            
            // Ekle butonunu güncelle butonuna çevir
            var btn = document.querySelector('.btn-primary');
            btn.innerHTML = '<i class="fas fa-save"></i> Güncelle';
            btn.onclick = function() {
                updateApi(id);
            };
        }

        function updateApi(id) {
            var endpoint = document.getElementById('endpoint').value.trim();
            var url = document.getElementById('url').value.trim();
            var description = document.getElementById('description').value.trim();
            var method = document.getElementById('method').value;
            var category = document.getElementById('category').value.trim();

            if (!endpoint || !url) {
                showToast('Endpoint ve URL gerekli!', 'error');
                return;
            }

            var formData = 'action=update_api&id=' + id +
                '&endpoint=' + encodeURIComponent(endpoint) +
                '&url=' + encodeURIComponent(url) +
                '&description=' + encodeURIComponent(description) +
                '&method=' + method +
                '&category=' + encodeURIComponent(category);

            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    document.getElementById('endpoint').value = '';
                    document.getElementById('url').value = '';
                    document.getElementById('description').value = '';
                    document.getElementById('category').value = '';
                    
                    var btn = document.querySelector('.btn-primary');
                    btn.innerHTML = '<i class="fas fa-plus-circle"></i> API Ekle';
                    btn.onclick = function() { addApi(); };
                    
                    loadApis();
                    showToast('API güncellendi! ✏️');
                } else {
                    showToast(data.message, 'error');
                }
            });
        }

        function showToast(message, type) {
            type = type || 'success';
            var container = document.getElementById('toastContainer');
            var toast = document.createElement('div');
            toast.className = 'toast';
            toast.textContent = message;
            if (type === 'error') toast.style.borderLeftColor = '#d63031';
            container.appendChild(toast);
            
            setTimeout(function() {
                toast.style.animation = 'slideIn 0.4s ease reverse forwards';
                setTimeout(function() { container.removeChild(toast); }, 400);
            }, 3000);
        }

        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && document.activeElement === document.getElementById('endpoint')) {
                addApi();
            }
        });

        loadApis();
    </script>
</body>
</html>
<?php endif; ?>
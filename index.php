<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - apiportali.kesug.com</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6c5ce7;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass: rgba(255, 255, 255, 0.95);
            --shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--gradient);
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 50%, rgba(255,255,255,0.1) 0%, transparent 50%);
            animation: bgPulse 15s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes bgPulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .main-container {
            position: relative; z-index: 2;
            width: 100%; max-width: 900px;
            margin: 0 auto; padding: 20px;
            min-height: 100vh;
            display: flex; justify-content: center; align-items: center;
        }

        .home-page {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 50px 40px;
            box-shadow: var(--shadow);
            text-align: center;
            width: 100%;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo { font-size: 3em; margin-bottom: 15px; }

        .title {
            font-size: 2.5em;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 5px;
            font-weight: 800;
        }

        .subtitle { font-size: 1.1em; color: #666; margin-bottom: 20px; }

        .domain-badge {
            background: var(--gradient);
            color: white;
            padding: 10px 25px;
            border-radius: 25px;
            display: inline-block;
            font-size: 0.95em;
            margin-bottom: 30px;
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        .stats-bar {
            display: flex; justify-content: center; gap: 20px;
            margin-bottom: 30px; flex-wrap: wrap;
        }

        .stat-item {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            padding: 15px 20px; border-radius: 12px; min-width: 100px;
        }

        .stat-number { font-size: 1.8em; font-weight: 700; color: #667eea; }
        .stat-label { font-size: 0.8em; color: #666; }

        .search-box { margin-bottom: 25px; position: relative; }

        .search-box input {
            width: 100%; padding: 15px 50px 15px 20px;
            border: 2px solid #e0e0e0; border-radius: 50px;
            font-size: 1em; transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none; border-color: #667eea;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2);
        }

        .search-box i {
            position: absolute; right: 20px; top: 50%;
            transform: translateY(-50%); color: #667eea;
        }

        .filter-buttons {
            display: flex; gap: 10px; margin-bottom: 25px;
            justify-content: center; flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 18px; border: 2px solid #e0e0e0;
            background: white; border-radius: 25px;
            cursor: pointer; transition: all 0.3s;
            font-weight: 500; font-size: 0.9em; color: #666;
        }

        .filter-btn:hover, .filter-btn.active {
            background: var(--gradient); color: white;
            border-color: transparent; transform: translateY(-2px);
        }

        .apis-container {
            display: flex; flex-direction: column; gap: 15px;
            max-height: 500px; overflow-y: auto; padding-right: 10px;
        }

        .apis-container::-webkit-scrollbar { width: 8px; }
        .apis-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .apis-container::-webkit-scrollbar-thumb { background: #667eea; border-radius: 10px; }

        .api-item {
            background: white; border-radius: 15px; padding: 20px;
            display: flex; align-items: center; justify-content: space-between;
            transition: all 0.3s ease; border: 2px solid #f0f0f0;
            animation: fadeIn 0.5s ease; position: relative; overflow: hidden;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .api-item::before {
            content: ''; position: absolute; left: 0; top: 0;
            height: 100%; width: 4px;
            background: var(--gradient); transition: width 0.3s;
        }

        .api-item:hover::before { width: 8px; }

        .api-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            border-color: #667eea;
        }

        .api-info { flex: 1; text-align: left; margin-right: 20px; }

        .api-endpoint {
            font-family: 'Courier New', monospace;
            font-size: 1.1em; color: #667eea;
            font-weight: 700; margin-bottom: 5px;
        }

        .api-url {
            font-size: 0.8em; color: #666; word-break: break-all;
            margin-bottom: 5px; font-family: monospace;
            background: #f8f9fa; padding: 5px 10px;
            border-radius: 5px; display: inline-block;
        }

        .api-meta {
            display: flex; gap: 15px; align-items: center;
            flex-wrap: wrap; margin-top: 5px;
        }

        .api-method {
            padding: 4px 12px; border-radius: 20px;
            font-size: 0.75em; font-weight: 700; text-transform: uppercase;
        }

        .method-GET { background: #00b894; color: white; }
        .method-POST { background: #0984e3; color: white; }
        .method-PUT { background: #fdcb6e; color: #333; }
        .method-DELETE { background: #d63031; color: white; }

        .api-category { font-size: 0.8em; color: #999; }
        .api-stats { font-size: 0.8em; color: #999; }

        .button-group { display: flex; gap: 10px; flex-wrap: wrap; }

        .btn {
            padding: 10px 20px; border: none; border-radius: 10px;
            font-size: 0.9em; cursor: pointer; transition: all 0.3s ease;
            font-weight: 600; white-space: nowrap;
            display: flex; align-items: center; gap: 6px;
            text-decoration: none;
        }

        .btn-primary { background: var(--gradient); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5); }
        .btn-secondary { background: #00b894; color: white; }
        .btn-secondary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0, 184, 148, 0.5); }
        .btn-outline { background: transparent; border: 2px solid #667eea; color: #667eea; }
        .btn-outline:hover { background: #667eea; color: white; }

        .empty-state { text-align: center; padding: 60px 20px; color: #999; }
        .empty-state i { font-size: 4em; margin-bottom: 20px; color: #ddd; }

        .toast-container { position: fixed; top: 30px; right: 30px; z-index: 10000; }

        .toast {
            background: white; padding: 15px 25px;
            border-radius: 12px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            margin-bottom: 10px; animation: slideInRight 0.4s ease;
            border-left: 5px solid #00b894; font-weight: 500;
        }

        @keyframes slideInRight {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .admin-link { position: fixed; bottom: 30px; right: 30px; z-index: 1000; }

        .admin-link a {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white; padding: 12px 22px;
            border-radius: 50px; text-decoration: none;
            font-weight: 600; transition: all 0.3s;
            display: flex; align-items: center; gap: 8px;
            font-size: 0.9em;
        }

        .admin-link a:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .home-page { padding: 30px 20px; }
            .title { font-size: 2em; }
            .api-item { flex-direction: column; align-items: flex-start; gap: 15px; }
            .button-group { width: 100%; }
            .btn { flex: 1; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="toast-container" id="toastContainer"></div>
    
    <div class="main-container">
        <div class="home-page">
            <div class="logo">⚡</div>
            <h1 class="title">API Portalı</h1>
            <p class="subtitle">API'lerini Paylaş, Kolayca Eriş</p>
            
            <div class="domain-badge">
                <i class="fas fa-link"></i> apiportali.kesug.com/api/
            </div>

            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-number" id="totalApis">0</div>
                    <div class="stat-label">Toplam API</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="todayApis">0</div>
                    <div class="stat-label">Bugün Eklenen</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="totalClicks">0</div>
                    <div class="stat-label">Toplam İstek</div>
                </div>
            </div>

            <div class="search-box">
                <input type="text" id="searchInput" placeholder="API ara... (endpoint, açıklama, kategori)" oninput="loadApis()">
                <i class="fas fa-search"></i>
            </div>

            <div class="filter-buttons">
                <button class="filter-btn active" onclick="setFilter('all', this)">Tümü</button>
                <button class="filter-btn" onclick="setFilter('today', this)">Bugün</button>
                <button class="filter-btn" onclick="setFilter('week', this)">Bu Hafta</button>
            </div>

            <div class="apis-container" id="apisContainer">
                <div class="empty-state">
                    <i class="fas fa-spinner fa-spin"></i>
                    <h3>Yükleniyor...</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-link">
        <a href="/admin">
            <i class="fas fa-lock"></i> Admin Panel
        </a>
    </div>

    <script>
        var currentFilter = 'all';

        window.onload = function() {
            loadApis();
            loadStats();
        };

        function loadApis() {
            var searchTerm = document.getElementById('searchInput').value;
            
            fetch('api.php?ajax=get_apis&filter=' + currentFilter + '&search=' + encodeURIComponent(searchTerm))
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.success) { renderApis(data.apis); }
                })
                .catch(function(error) { console.error('Hata:', error); });
        }

        function loadStats() {
            fetch('api.php?ajax=get_stats')
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.success) {
                        document.getElementById('totalApis').textContent = data.stats.total;
                        document.getElementById('todayApis').textContent = data.stats.today;
                        document.getElementById('totalClicks').textContent = data.stats.clicks;
                    }
                });
        }

        function renderApis(apis) {
            var container = document.getElementById('apisContainer');
            
            if (apis.length === 0) {
                container.innerHTML = '<div class="empty-state"><i class="fas fa-code"></i><h3>Henüz API eklenmemiş</h3><p>Admin panelinden yeni API\'ler ekleyebilirsiniz</p></div>';
                return;
            }

            var html = '';
            for (var i = 0; i < apis.length; i++) {
                var api = apis[i];
                var date = new Date(api.created_at);
                var formattedDate = date.toLocaleDateString('tr-TR', { day: 'numeric', month: 'long', year: 'numeric' });
                var shortUrl = api.url.length > 60 ? api.url.substring(0, 60) + '...' : api.url;
                
                html += '<div class="api-item">';
                html += '<div class="api-info">';
                html += '<div class="api-endpoint"><i class="fas fa-terminal"></i> /api/' + api.endpoint + '</div>';
                if (api.description) {
                    html += '<div style="color: #333; margin: 8px 0; font-size: 0.95em;">' + api.description + '</div>';
                }
                html += '<div class="api-meta">';
                html += '<span class="api-method method-' + api.method + '">' + api.method + '</span>';
                if (api.category) {
                    html += '<span class="api-category"><i class="fas fa-folder"></i> ' + api.category + '</span>';
                }
                html += '<span class="api-stats"><i class="fas fa-mouse-pointer"></i> ' + api.click_count + ' istek • <i class="far fa-clock"></i> ' + formattedDate + '</span>';
                html += '</div>';
                html += '<div class="api-url" style="margin-top: 8px;">' + shortUrl + '</div>';
                html += '</div>';
                html += '<div class="button-group">';
                html += '<button class="btn btn-primary" onclick="goToApi(\'' + api.endpoint + '\', ' + api.id + ')"><i class="fas fa-external-link-alt"></i> API\'ye Git</button>';
                html += '<button class="btn btn-secondary" onclick="copyEndpoint(\'' + api.endpoint + '\')"><i class="fas fa-copy"></i> Kopyala</button>';
                html += '<button class="btn btn-outline" onclick="copyText(\'' + api.url.replace(/'/g, "\\'") + '\')"><i class="fas fa-link"></i> URL</button>';
                html += '</div>';
                html += '</div>';
            }
            
            container.innerHTML = html;
        }

        function goToApi(endpoint, id) {
            var formData = 'action=track_click&id=' + id;
            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            }).then(function() { loadStats(); });
            
            window.open('api/' + endpoint + '?redirect=1', '_blank');
        }

        function copyEndpoint(endpoint) {
            var fullUrl = 'apiportali.kesug.com/api/' + endpoint;
            copyText(fullUrl);
        }

        function copyText(text) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(function() { showToast('Kopyalandı! 📋'); });
            } else {
                var textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showToast('Kopyalandı! 📋');
            }
        }

        function setFilter(filter, element) {
            currentFilter = filter;
            var buttons = document.querySelectorAll('.filter-btn');
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].classList.remove('active');
            }
            element.classList.add('active');
            loadApis();
        }

        function showToast(message) {
            var container = document.getElementById('toastContainer');
            var toast = document.createElement('div');
            toast.className = 'toast';
            toast.textContent = message;
            container.appendChild(toast);
            
            setTimeout(function() {
                toast.style.animation = 'slideInRight 0.4s ease reverse forwards';
                setTimeout(function() { container.removeChild(toast); }, 400);
            }, 3000);
        }

        setInterval(loadStats, 30000);
    </script>
</body>
</html
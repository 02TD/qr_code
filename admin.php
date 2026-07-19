<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель | Анонимді хаттар</title>
    <style>
        :root {
            --primary-color: #0f2b5b;
            --secondary-color: #1a448a;
            --bg-color: #f4f6f9;
            --white: #ffffff;
            --text-main: #333333;
            --text-muted: #6c757d;
            --border-color: #dce1e8;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* --- СОЛ ЖАҚ МӘЗІР (SIDEBAR) --- */
        .sidebar {
            width: 260px;
            background-color: var(--primary-color);
            color: var(--white);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header img {
            max-width: 60px;
            margin-bottom: 10px;
        }

        .sidebar-header h2 {
            font-size: 16px;
            font-weight: 600;
        }

        .nav-menu {
            list-style: none;
            padding: 20px 0;
            flex-grow: 1;
        }

        .nav-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .nav-item:hover, .nav-item.active {
            background-color: var(--secondary-color);
            color: var(--white);
            border-left: 4px solid var(--warning);
        }

        .nav-item svg {
            width: 20px;
            height: 20px;
        }

        /* --- НЕГІЗГІ МАЗМҰН (MAIN CONTENT) --- */
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            width: calc(100% - 260px);
        }

        /* Жоғарғы панель */
        .topbar {
            background-color: var(--white);
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            z-index: 10;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--primary-color);
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        /* Дашборд мазмұны */
        .dashboard-container {
            padding: 25px;
            overflow-y: auto;
        }

        .page-title {
            margin-bottom: 20px;
            font-size: 24px;
            color: var(--primary-color);
        }

        /* Статистика карточкалары */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: var(--white);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            gap: 15px;
            border-left: 4px solid var(--primary-color);
        }

        .stat-card.warning { border-left-color: var(--warning); }
        .stat-card.success { border-left-color: var(--success); }

        .stat-icon {
            background-color: var(--bg-color);
            padding: 15px;
            border-radius: 8px;
            color: var(--primary-color);
        }

        .stat-info h3 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .stat-info p {
            color: var(--text-muted);
            font-size: 14px;
        }

        /* Кесте (Хаттар тізімі) */
        .table-wrapper {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            overflow: hidden;
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px; /* Телефонда көлденең айналдыру үшін */
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: #fcfcfc;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 14px;
        }

        td {
            font-size: 15px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-new { background-color: #fff3cd; color: #856404; }
        .status-read { background-color: #d4edda; color: #155724; }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            transition: 0.2s;
        }

        .btn-view { background-color: var(--primary-color); color: var(--white); }
        .btn-view:hover { background-color: var(--secondary-color); }
        
        .btn-delete { background-color: #fee2e2; color: var(--danger); margin-left: 5px; }
        .btn-delete:hover { background-color: #fca5a5; color: white; }

        /* ТЕЛЕФОНҒА БЕЙІМДЕЛУІ (Mobile Responsive) */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                height: 100vh;
                transform: translateX(-100%); /* Басында жасырылып тұрады */
            }

            .sidebar.active {
                transform: translateX(0); /* Батырма басылғанда шығады */
            }

            .main-content {
                width: 100%;
            }

            .menu-toggle {
                display: block; /* Телефонда мәзір ашатын батырма көрсетіледі */
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Сол жақ мәзір -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="logo.png" alt="Логотип" onerror="this.style.display='none'">
            <h2>Анонимді хат<br>Админ панель</h2>
        </div>
        <ul class="nav-menu">
            <li>
                <a href="#" class="nav-item active">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Келген хаттар
                </a>
            </li>
            <li>
                <a href="#" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    Мұрағат
                </a>
            </li>
            <li style="margin-top: 50px;">
                <a href="#" class="nav-item" style="color: #ffc107;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Шығу
                </a>
            </li>
        </ul>
    </aside>

    <!-- Негізгі бөлік -->
    <main class="main-content">
        <!-- Жоғарғы топбар -->
        <header class="topbar">
            <button class="menu-toggle" id="menuToggle">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div class="admin-profile">
                <span>Администратор</span>
                <svg width="32" height="32" viewBox="0 0 24 24" fill="#0f2b5b"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
            </div>
        </header>

        <!-- Дашборд -->
        <div class="dashboard-container">
            <h1 class="page-title">Бақылау тақтасы</h1>

            <!-- Статистика -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <div class="stat-info">
                        <h3>142</h3>
                        <p>Барлық хаттар</p>
                    </div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-icon">
                        <svg width="28" height="28" fill="none" stroke="#ffc107" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <div class="stat-info">
                        <h3>5</h3>
                        <p>Жаңа хаттар</p>
                    </div>
                </div>
                <div class="stat-card success">
                    <div class="stat-icon">
                        <svg width="28" height="28" fill="none" stroke="#28a745" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="stat-info">
                        <h3>137</h3>
                        <p>Қаралған хаттар</p>
                    </div>
                </div>
            </div>

            <!-- Хаттар кестесі -->
            <div class="table-wrapper">
                <div class="table-header">
                    <h2>Келген хаттар тізімі</h2>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Күні / Уақыты</th>
                                <th>Мәтін үзіндісі</th>
                                <th>Тіркелген файл</th>
                                <th>Статус</th>
                                <th>Әрекет</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- PHP арқылы циклмен шығарылатын мәліметтер осында болады. Үлгі: -->
                            <tr>
                                <td>#1042</td>
                                <td>20.07.2026<br><small style="color: #666;">10:35</small></td>
                                <td>Тексеріс барысында байқалған заң бұзушылықтар...</td>
                                <td><a href="#" style="color: #1a448a; text-decoration: none;">📄 Сурет_1.jpg</a></td>
                                <td><span class="status-badge status-new">Жаңа</span></td>
                                <td>
                                    <button class="action-btn btn-view">Оқу</button>
                                    <button class="action-btn btn-delete">Өшіру</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#1041</td>
                                <td>19.07.2026<br><small style="color: #666;">16:20</small></td>
                                <td>Әскери бөлімдегі жабдықтау мәселесі бойынша...</td>
                                <td>Жоқ</td>
                                <td><span class="status-badge status-read">Қаралды</span></td>
                                <td>
                                    <button class="action-btn btn-view">Ашу</button>
                                    <button class="action-btn btn-delete">Өшіру</button>
                                </td>
                            </tr>
                            <tr>
                                <td>#1040</td>
                                <td>18.07.2026<br><small style="color: #666;">09:15</small></td>
                                <td>Техникалық қауіпсіздік ережелерінің сақталмауы...</td>
                                <td><a href="#" style="color: #1a448a; text-decoration: none;">📁 Video.mp4</a></td>
                                <td><span class="status-badge status-read">Қаралды</span></td>
                                <td>
                                    <button class="action-btn btn-view">Ашу</button>
                                    <button class="action-btn btn-delete">Өшіру</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Телефонда мәзірді ашып-жабу логикасы
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Телефонда мәзірден тыс жерді басқанда мәзір жабылады
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>
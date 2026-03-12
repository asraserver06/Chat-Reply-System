<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin – {{ config('app.name', 'CRS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── CSS Variables ── */
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #0f172a;
            --sidebar-border: #1e293b;
            --sidebar-text: #94a3b8;
            --sidebar-active-bg: #1e3a8a;
            --sidebar-active-text: #fff;
            --topbar-height: 64px;
            --accent: #3b82f6;
            --accent-hover: #2563eb;
            --bg-main: #f1f5f9;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-main);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 50;
            transition: transform .3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 24px;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .sidebar-brand .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }

        .sidebar-brand .brand-name {
            color: #fff;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: .5px;
        }

        .sidebar-brand .brand-sub {
            color: var(--sidebar-text);
            font-size: 11px;
            font-weight: 400;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
        }

        .nav-section-label {
            color: #475569;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 6px 12px;
            margin-top: 8px;
            margin-bottom: 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all .18s ease;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: rgba(255,255,255,.06);
            color: #cbd5e1;
        }

        .nav-item.active {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
        }

        .nav-item .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .nav-item .nav-badge {
            margin-left: auto;
            background: var(--accent);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--sidebar-border);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            background: rgba(255,255,255,.05);
        }

        .sidebar-user .avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 14px;
            flex-shrink: 0;
        }

        .sidebar-user .user-name {
            color: #e2e8f0;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .sidebar-user .user-role {
            color: var(--sidebar-text);
            font-size: 11px;
        }

        .sidebar-user .logout-btn {
            margin-left: auto;
            background: none; border: none; cursor: pointer;
            color: #475569; font-size: 16px;
            transition: color .15s;
        }

        .sidebar-user .logout-btn:hover { color: #ef4444; }

        /* ── Main Content ── */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky; top: 0; z-index: 40;
        }

        .topbar-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-badge {
            width: 36px; height: 36px;
            background: var(--bg-main);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
            font-size: 16px;
            cursor: pointer;
            transition: background .15s;
        }

        .topbar-badge:hover { background: #e2e8f0; }

        .page-content {
            flex: 1;
            padding: 28px;
        }

        /* ── Flash messages ── */
        .flash {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }
        .flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .flash-error   { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }

        /* ── Stat Cards ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 14px;
            padding: 24px;
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: box-shadow .2s;
        }

        .stat-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.07); }

        .stat-card-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }

        .stat-card-icon.blue   { background: #eff6ff; }
        .stat-card-icon.green  { background: #f0fdf4; }
        .stat-card-icon.purple { background: #faf5ff; }
        .stat-card-icon.orange { background: #fff7ed; }

        .stat-card-value {
            font-size: 30px;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1;
        }

        .stat-card-label {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ── Data Table ── */
        .card {
            background: var(--card-bg);
            border-radius: 14px;
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
        }

        .card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-main);
        }

        .card-body { padding: 0; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        thead th {
            background: #f8fafc;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .7px;
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        tbody td {
            padding: 14px 20px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-main);
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }

        tbody tr:hover { background: #f8fafc; }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .3px;
        }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-red    { background: #fee2e2; color: #dc2626; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-purple { background: #ede9fe; color: #6d28d9; }
        .badge-gray   { background: #f1f5f9; color: #475569; }
        .badge-yellow { background: #fef9c3; color: #a16207; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all .15s;
        }
        .btn-primary   { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-hover); }
        .btn-danger    { background: #ef4444; color: #fff; }
        .btn-danger:hover  { background: #dc2626; }
        .btn-outline   { background: transparent; border: 1px solid var(--border); color: var(--text-muted); }
        .btn-outline:hover { background: #f8fafc; }
        .btn-sm        { padding: 6px 12px; font-size: 12px; }

        /* ── Pagination ── */
        .pagination-wrap {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
        }
    </style>
</head>
<body>

<!-- ── Sidebar ── -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">💬</div>
        <div>
            <div class="brand-name">CRS Admin</div>
            <div class="brand-sub">Management Panel</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Dashboard
        </a>

        <div class="nav-section-label">Management</div>
        <a href="{{ route('admin.users.index') }}"
           class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span> Users
        </a>
        <a href="{{ route('admin.messages.index') }}"
           class="nav-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
            <span class="nav-icon">💬</span> Chats & Messages
        </a>
        <a href="{{ route('admin.subscriptions.index') }}"
           class="nav-item {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
            <span class="nav-icon">💳</span> Subscriptions
        </a>

        <div class="nav-section-label">Account</div>
        <a href="{{ route('profile.edit') }}"
           class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <span class="nav-icon">⚙️</span> Settings
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div style="min-width:0;">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">Administrator</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" title="Logout">🚪</button>
            </form>
        </div>
    </div>
</aside>

<!-- ── Main ── -->
<div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-right">
            <div class="topbar-badge" title="Notifications">🔔</div>
            <div class="topbar-badge" title="Admin">👤</div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @if(session('success'))
            <div class="flash flash-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash flash-error">❌ {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

</body>
</html>

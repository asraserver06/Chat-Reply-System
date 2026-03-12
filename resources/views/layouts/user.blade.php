<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CRS') }} – @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #0f172a; margin: 0; min-height: 100vh; }

        /* ── Top Nav ── */
        .user-nav {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: sticky; top: 0; z-index: 40;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }

        .nav-brand {
            display: flex; align-items: center; gap: 10px;
            font-weight: 700; color: #0f172a; font-size: 16px;
            text-decoration: none;
        }

        .nav-brand .brand-dot {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
        }

        .nav-links {
            display: flex; align-items: center; gap: 4px;
        }

        .nav-link {
            padding: 7px 14px;
            border-radius: 8px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all .15s;
            display: flex; align-items: center; gap: 7px;
        }

        .nav-link:hover { background: #f1f5f9; color: #0f172a; }
        .nav-link.active { background: #ede9fe; color: #6d28d9; }

        .nav-right {
            display: flex; align-items: center; gap: 10px;
        }

        .nav-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 13px;
            cursor: pointer;
        }

        .nav-plan-badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            background: #ede9fe;
            color: #6d28d9;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .logout-form button {
            background: none; border: none; padding: 7px 14px;
            border-radius: 8px; color: #64748b; font-size: 14px;
            font-weight: 500; cursor: pointer; transition: all .15s;
            display: flex; align-items: center; gap: 7px;
        }

        .logout-form button:hover { background: #fee2e2; color: #dc2626; }

        /* ── Page wrapper ── */
        .page-wrapper { min-height: calc(100vh - 60px); }
    </style>
</head>
<body>

<!-- Top Navigation -->
<nav class="user-nav">
    <a href="{{ route('user.dashboard') }}" class="nav-brand">
        <div class="brand-dot">💬</div>
        CRS
    </a>

    <div class="nav-links">
        <a href="{{ route('user.dashboard') }}"
           class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            📊 Dashboard
        </a>
        <a href="{{ route('user.chats.index') }}"
           class="nav-link {{ request()->routeIs('user.chats.*') ? 'active' : '' }}">
            💬 My Chats
        </a>
        <a href="{{ route('user.subscription.index') }}"
           class="nav-link {{ request()->routeIs('user.subscription.*') ? 'active' : '' }}">
            🚀 Subscription
        </a>
        <a href="{{ route('profile.edit') }}"
           class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            ⚙️ Profile
        </a>
    </div>

    <div class="nav-right">
        <span class="nav-plan-badge">{{ auth()->user()->currentPlanSlug() }}</span>
        <div class="nav-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit">🚪 Logout</button>
        </form>
    </div>
</nav>

<div class="page-wrapper">
    @yield('content')
</div>

</body>
</html>

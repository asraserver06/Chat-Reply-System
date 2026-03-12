@extends('layouts.admin')  
@section('page-title', 'Dashboard')

@section('content')

<!-- ── Stat Cards ── -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-card-icon blue">👥</div>
        <div class="stat-card-value">{{ number_format($stats['total_users']) }}</div>
        <div class="stat-card-label">Total Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon green">💬</div>
        <div class="stat-card-value">{{ number_format($stats['total_chats']) }}</div>
        <div class="stat-card-label">Total Chats</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon purple">✉️</div>
        <div class="stat-card-value">{{ number_format($stats['total_messages']) }}</div>
        <div class="stat-card-label">Total Messages</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon orange">🟢</div>
        <div class="stat-card-value">{{ number_format($stats['active_chats']) }}</div>
        <div class="stat-card-label">Active Chats</div>
    </div>
</div>

<!-- ── Quick Links ── -->
<div style="display:grid; grid-template-columns: repeat(auto-fill,minmax(280px,1fr)); gap:20px;">

    <div class="card">
        <div class="card-header">
            <span class="card-title">👥 User Management</span>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">View All</a>
        </div>
        <div style="padding:20px; color:var(--text-muted); font-size:14px; line-height:1.7;">
            Manage user accounts, view their chats and subscription status, and remove accounts if needed.
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">💬 Chat Management</span>
            <a href="{{ route('admin.messages.index') }}" class="btn btn-primary btn-sm">View All</a>
        </div>
        <div style="padding:20px; color:var(--text-muted); font-size:14px; line-height:1.7;">
            Browse all user chats, inspect individual messages, and monitor automated reply activity.
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title">💳 Subscriptions</span>
            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-primary btn-sm">View All</a>
        </div>
        <div style="padding:20px; color:var(--text-muted); font-size:14px; line-height:1.7;">
            View all subscription plans, see which users are subscribed, and manage active plans.
        </div>
    </div>

</div>

@endsection

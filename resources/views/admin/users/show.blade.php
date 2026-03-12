@extends('layouts.admin')
@section('page-title', 'User: ' . $user->name)

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">← Back to Users</a>
</div>

<div style="display:grid; grid-template-columns:340px 1fr; gap:24px; align-items:start;">

    <!-- ── Profile Card ── -->
    <div class="card" style="padding:28px; text-align:center;">
        <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a855f7);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:26px;margin:0 auto 16px;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div style="font-size:20px; font-weight:700; margin-bottom:4px;">{{ $user->name }}</div>
        <div style="color:var(--text-muted); font-size:14px; margin-bottom:16px;">{{ $user->email }}</div>

        <div style="display:flex; justify-content:center; gap:8px; flex-wrap:wrap; margin-bottom:20px;">
            @foreach($user->roles as $role)
                <span class="badge {{ $role->name === 'Admin' ? 'badge-purple' : 'badge-blue' }}">{{ $role->name }}</span>
            @endforeach
            @if($user->email_verified_at)
                <span class="badge badge-green">✔ Verified</span>
            @else
                <span class="badge badge-yellow">⏳ Not Verified</span>
            @endif
        </div>

        <div style="text-align:left; background:#f8fafc; border-radius:10px; padding:16px; font-size:13px; line-height:2;">
            <div><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</div>
            <div><strong>Chats:</strong> {{ $user->chats->count() }}</div>
            <div><strong>Subscriptions:</strong> {{ $user->subscriptions->count() }}</div>
        </div>

        @if(auth()->id() !== $user->id)
        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="margin-top:20px;"
              onsubmit="return confirm('Permanently delete this user?');">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger" style="width:100%;">🗑 Delete User</button>
        </form>
        @endif
    </div>

    <!-- ── Chats & Subscriptions ── -->
    <div>
        <!-- Chats -->
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header">
                <span class="card-title">💬 Chats ({{ $user->chats->count() }})</span>
            </div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Messages</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->chats as $chat)
                        <tr>
                            <td style="font-weight:600;">{{ $chat->title ?? 'Untitled' }}</td>
                            <td>{{ $chat->messages->count() }}</td>
                            <td>
                                <span class="badge {{ $chat->is_active ? 'badge-green' : 'badge-gray' }}">
                                    {{ $chat->is_active ? 'Active' : 'Closed' }}
                                </span>
                            </td>
                            <td style="color:var(--text-muted); font-size:13px;">{{ $chat->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.messages.show', $chat) }}" class="btn btn-outline btn-sm">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px; color:var(--text-muted);">No chats yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Subscriptions -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">💳 Subscriptions ({{ $user->subscriptions->count() }})</span>
            </div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Ends At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->subscriptions as $sub)
                        <tr>
                            <td style="font-weight:600;">{{ $sub->name }}</td>
                            <td>
                                <span class="badge {{ $sub->active() ? 'badge-green' : 'badge-red' }}">
                                    {{ $sub->active() ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td style="color:var(--text-muted); font-size:13px;">
                                {{ $sub->ends_at ? $sub->ends_at->format('M d, Y') : '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align:center; padding:30px; color:var(--text-muted);">No subscriptions.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@extends('layouts.admin')
@section('page-title', 'Chats & Messages')

@section('content')

<div class="card">
    <div class="card-header">
        <span class="card-title">💬 All Chats</span>
        <span style="color:var(--text-muted); font-size:13px;">{{ $chats->total() }} total</span>
    </div>
    <div class="card-body">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>User</th>
                    <th>Messages</th>
                    <th>Status</th>
                    <th>Updated</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($chats as $chat)
                <tr>
                    <td style="color:var(--text-muted); font-size:12px;">{{ $chat->id }}</td>
                    <td style="font-weight:600;">{{ $chat->title ?? 'Untitled Chat' }}</td>
                    <td>
                        @if($chat->user)
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a855f7);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:11px;flex-shrink:0;">
                                    {{ strtoupper(substr($chat->user->name, 0, 1)) }}
                                </div>
                                <a href="{{ route('admin.users.show', $chat->user) }}" style="color:var(--accent); text-decoration:none;">
                                    {{ $chat->user->name }}
                                </a>
                            </div>
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-blue">{{ $chat->messages->count() }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $chat->is_active ? 'badge-green' : 'badge-gray' }}">
                            {{ $chat->is_active ? 'Active' : 'Closed' }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted); font-size:13px;">{{ $chat->updated_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.messages.show', $chat) }}" class="btn btn-outline btn-sm">👁 View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted);">No chats found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($chats->hasPages())
    <div class="pagination-wrap">
        {{ $chats->links() }}
    </div>
    @endif
</div>

@endsection

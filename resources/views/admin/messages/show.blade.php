@extends('layouts.admin')
@section('page-title', 'Chat: ' . ($chat->title ?? 'Untitled'))

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.messages.index') }}" class="btn btn-outline btn-sm">← Back to Chats</a>
</div>

<div style="display:grid; grid-template-columns:280px 1fr; gap:24px; align-items:start;">

    <!-- Chat Info -->
    <div class="card" style="padding:24px;">
        <div style="font-size:16px; font-weight:700; margin-bottom:16px;">💬 Chat Info</div>

        <div style="font-size:13px; line-height:2.2; color:var(--text-muted);">
            <div><strong style="color:var(--text-main);">ID:</strong> #{{ $chat->id }}</div>
            <div><strong style="color:var(--text-main);">Title:</strong> {{ $chat->title ?? 'Untitled' }}</div>
            <div>
                <strong style="color:var(--text-main);">Status:</strong>
                <span class="badge {{ $chat->is_active ? 'badge-green' : 'badge-gray' }}">
                    {{ $chat->is_active ? 'Active' : 'Closed' }}
                </span>
            </div>
            <div><strong style="color:var(--text-main);">Created:</strong> {{ $chat->created_at->format('M d, Y H:i') }}</div>
            <div><strong style="color:var(--text-main);">Messages:</strong> {{ $chat->messages->count() }}</div>
        </div>

        @if($chat->user)
        <div style="margin-top:20px; padding-top:16px; border-top:1px solid var(--border);">
            <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.7px; color:var(--text-muted); margin-bottom:10px;">Owner</div>
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a855f7);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;">
                    {{ strtoupper(substr($chat->user->name, 0, 1)) }}
                </div>
                <div>
                    <a href="{{ route('admin.users.show', $chat->user) }}" style="color:var(--accent); text-decoration:none; font-weight:600; font-size:14px;">
                        {{ $chat->user->name }}
                    </a>
                    <div style="font-size:12px; color:var(--text-muted);">{{ $chat->user->email }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Messages Thread -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">✉️ Messages ({{ $chat->messages->count() }})</span>
        </div>
        <div style="padding:20px; display:flex; flex-direction:column; gap:14px; max-height:600px; overflow-y:auto;">
            @forelse($chat->messages as $message)
            <div style="display:flex; gap:12px; align-items:flex-start; {{ $message->is_auto_reply ? 'flex-direction:row-reverse;' : '' }}">
                <div style="width:32px;height:32px;border-radius:50%;background:{{ $message->is_auto_reply ? 'linear-gradient(135deg,#22c55e,#16a34a)' : 'linear-gradient(135deg,#6366f1,#a855f7)' }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;flex-shrink:0;">
                    {{ $message->is_auto_reply ? '🤖' : strtoupper(substr($message->user?->name ?? '?', 0, 1)) }}
                </div>
                <div style="max-width:75%;">
                    <div style="font-size:11px; color:var(--text-muted); margin-bottom:4px; {{ $message->is_auto_reply ? 'text-align:right;' : '' }}">
                        {{ $message->is_auto_reply ? 'Auto Reply' : ($message->user?->name ?? 'Unknown') }}
                        · {{ $message->created_at->format('M d, H:i') }}
                        @if($message->read_at)
                            · <span style="color:var(--success);">✔ Read</span>
                        @endif
                    </div>
                    <div style="background:{{ $message->is_auto_reply ? '#f0fdf4' : '#eff6ff' }};border:1px solid {{ $message->is_auto_reply ? '#bbf7d0' : '#bfdbfe' }};border-radius:12px;padding:10px 14px;font-size:14px;line-height:1.6;color:var(--text-main);">
                        {{ $message->body }}
                    </div>
                    @if($message->is_auto_reply)
                        <div style="font-size:11px; text-align:right; margin-top:4px;">
                            <span class="badge badge-green">🤖 Auto Reply</span>
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div style="text-align:center; padding:40px; color:var(--text-muted);">No messages in this chat.</div>
            @endforelse
        </div>
    </div>

</div>

@endsection

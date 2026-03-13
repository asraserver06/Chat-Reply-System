@extends('layouts.user')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

        {{-- Back + Title --}}
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('user.chats.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">← Back</a>
            <h1 class="text-xl font-bold text-gray-800">{{ $chat->title }}</h1>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $chat->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $chat->is_active ? 'Active' : 'Closed' }}
            </span>
            {{-- Delete this chat --}}
            <form method="POST" action="{{ route('user.chats.destroy', $chat) }}"
                  onsubmit="return confirm('Delete this chat and all its messages? This cannot be undone.')"
                  class="ml-auto">
                @csrf @method('DELETE')
                <button type="submit"
                    title="Delete chat"
                    class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg border border-transparent hover:border-red-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Chat
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-4">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4">⚠️ {{ session('error') }}</div>
        @endif

        {{-- Message thread --}}
        <div id="chatMessages" class="bg-white rounded-2xl shadow p-4 space-y-4 min-h-[400px] max-h-[60vh] overflow-y-auto mb-4">
            @forelse($chat->messages as $message)
            @php
                $isMine = $message->user_id === auth()->id();
                $isAuto = $message->is_auto_reply;
            @endphp
            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} group">
                <div class="max-w-[75%]">
                    {{-- Author label --}}
                    <div class="text-xs text-gray-400 mb-1 {{ $isMine ? 'text-right' : 'text-left' }}">
                        {{ $isAuto ? '🤖 Auto Reply' : ($isMine ? 'You' : ($message->user?->name ?? 'System')) }}
                        · {{ $message->created_at->format('h:i A') }}
                    </div>
                    {{-- Bubble + delete --}}
                    <div class="flex items-end gap-1 {{ $isMine ? 'flex-row-reverse' : '' }}">
                        <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                            {{ $isMine
                                ? 'bg-indigo-600 text-white rounded-br-none'
                                : ($isAuto
                                    ? 'bg-amber-50 border border-amber-200 text-amber-900 rounded-bl-none'
                                    : 'bg-gray-100 text-gray-800 rounded-bl-none') }}">
                            {{ $message->body }}
                        </div>
                        {{-- Delete button: only for own, non-auto-reply messages --}}
                        @if($isMine && !$isAuto)
                        <form method="POST" action="{{ route('user.messages.destroy', $message) }}"
                              onsubmit="return confirm('Delete this message?')"
                              class="opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                            @csrf @method('DELETE')
                            <button type="submit"
                                title="Delete message"
                                class="text-gray-300 hover:text-red-500 transition text-xs p-1 rounded">
                                ✕
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                <div class="text-4xl mb-3">💬</div>
                <p class="text-sm">No messages yet. Send the first one!</p>
            </div>
            @endforelse
        </div>

        {{-- Send Message Form --}}
        <div class="bg-white rounded-2xl shadow p-4">
            <form method="POST" action="{{ route('user.chats.send', $chat) }}" class="flex gap-3 items-end">
                @csrf
                <textarea name="body" rows="2" required maxlength="5000"
                    placeholder="Type your message…"
                    class="flex-1 border border-gray-300 rounded-xl px-4 py-2.5 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();this.form.submit();}"></textarea>
                <button type="submit"
                    class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-indigo-700 transition h-[44px] flex items-center gap-1.5 text-sm">
                    Send ↑
                </button>
            </form>
            <p class="text-xs text-gray-400 mt-2">Press <kbd class="px-1 py-0.5 bg-gray-100 rounded text-xs">Enter</kbd> to send, <kbd class="px-1 py-0.5 bg-gray-100 rounded text-xs">Shift+Enter</kbd> for new line.</p>
        </div>

    </div>
</div>

{{-- Auto-scroll to bottom --}}
<script>
    const chatMessages = document.getElementById('chatMessages');
    if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
</script>
@endsection

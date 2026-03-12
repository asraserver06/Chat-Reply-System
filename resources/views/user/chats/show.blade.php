@extends('layouts.app')

@section('content')
<div class="py-8">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

        {{-- Back + Title --}}
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('user.chats.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">← Back</a>
            <h1 class="text-xl font-bold text-gray-800">{{ $chat->title }}</h1>
            <span class="ml-auto px-2.5 py-0.5 rounded-full text-xs font-medium {{ $chat->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $chat->is_active ? 'Active' : 'Closed' }}
            </span>
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
            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[75%]">
                    {{-- Author label --}}
                    <div class="text-xs text-gray-400 mb-1 {{ $isMine ? 'text-right' : 'text-left' }}">
                        {{ $isAuto ? '🤖 Auto Reply' : ($isMine ? 'You' : ($message->user?->name ?? 'System')) }}
                        · {{ $message->created_at->format('h:i A') }}
                    </div>
                    {{-- Bubble --}}
                    <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                        {{ $isMine
                            ? 'bg-indigo-600 text-white rounded-br-none'
                            : ($isAuto
                                ? 'bg-amber-50 border border-amber-200 text-amber-900 rounded-bl-none'
                                : 'bg-gray-100 text-gray-800 rounded-bl-none') }}">
                        {{ $message->body }}
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

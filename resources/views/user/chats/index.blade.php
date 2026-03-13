@extends('layouts.user')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">My Chats</h1>
                <p class="text-gray-500 text-sm mt-1">{{ $chats->total() }} thread(s)</p>
            </div>

            {{-- New Chat Modal Trigger --}}
            <button onclick="document.getElementById('newChatModal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2.5 rounded-xl font-medium hover:bg-indigo-700 transition shadow">
                + New Chat
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">⚠️ {{ session('error') }}</div>
        @endif

        {{-- Chat list --}}
        <div class="bg-white rounded-2xl shadow divide-y">
            @forelse($chats as $chat)
            <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition group">
                <a href="{{ route('user.chats.show', $chat) }}" class="flex items-center gap-4 flex-1 min-w-0">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($chat->title, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-gray-800 truncate">{{ $chat->title }}</div>
                        <div class="text-xs text-gray-400 truncate mt-0.5">
                            {{ $chat->messages->first()?->body ?? 'No messages yet.' }}
                        </div>
                    </div>
                    <div class="text-xs text-gray-400 whitespace-nowrap">{{ $chat->updated_at->diffForHumans() }}</div>
                </a>
                {{-- Delete button --}}
                <form method="POST" action="{{ route('user.chats.destroy', $chat) }}"
                      onsubmit="return confirm('Are you sure you want to delete this chat and all its messages? This cannot be undone.')"
                      class="flex-shrink-0">
                    @csrf @method('DELETE')
                    <button type="submit"
                        title="Delete chat"
                        class="text-gray-300 hover:text-red-500 transition p-2 rounded-lg hover:bg-red-50"
                        aria-label="Delete chat">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
            @empty
            <div class="px-6 py-16 text-center text-gray-400">
                <div class="text-5xl mb-4">💬</div>
                <p class="font-semibold text-gray-600 text-lg">No chats yet!</p>
                <p class="text-sm mt-1">Start a new chat thread to get going.</p>
            </div>
            @endforelse
        </div>

        {{ $chats->links() }}
    </div>
</div>

{{-- New Chat Modal --}}
<div id="newChatModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Start a New Chat</h2>
        <form method="POST" action="{{ route('user.chats.store') }}">
            @csrf
            <label class="block text-sm font-medium text-gray-700 mb-1">Chat Title</label>
            <input type="text" name="title" placeholder="e.g. Support Request"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                   required maxlength="100">
            <div class="flex gap-3 mt-4 justify-end">
                <button type="button" onclick="document.getElementById('newChatModal').classList.add('hidden')"
                    class="px-4 py-2 rounded-xl border text-sm text-gray-600 hover:bg-gray-50">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Create</button>
            </div>
        </form>
    </div>
</div>
@endsection

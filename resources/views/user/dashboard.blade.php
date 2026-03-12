@extends('layouts.user')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Welcome Banner --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-indigo-200 mt-1">Here's what's happening with your chats today.</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow p-6 flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-indigo-100 flex items-center justify-center text-2xl">💬</div>
                <div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['total_chats'] }}</div>
                    <div class="text-sm text-gray-500">Total Chats</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center text-2xl">📨</div>
                <div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['total_messages'] }}</div>
                    <div class="text-sm text-gray-500">Messages Sent</div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow p-6 flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-yellow-100 flex items-center justify-center text-2xl">🔔</div>
                <div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['unread_messages'] }}</div>
                    <div class="text-sm text-gray-500">Unread Messages</div>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">⚠️ {{ session('error') }}</div>
        @endif

        {{-- Recent Chats --}}
        <div class="bg-white rounded-2xl shadow">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">Recent Chats</h2>
                <a href="{{ route('user.chats.index') }}" class="text-sm text-indigo-600 hover:underline">View all →</a>
            </div>
            <div class="divide-y">
                @forelse($recentChats as $chat)
                <a href="{{ route('user.chats.show', $chat) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition">
                    <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($chat->title, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium text-gray-800 truncate">{{ $chat->title }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ $chat->messages->first()?->body ?? 'No messages yet' }}</div>
                    </div>
                    <div class="text-xs text-gray-400 whitespace-nowrap">{{ $chat->updated_at->diffForHumans() }}</div>
                </a>
                @empty
                <div class="px-6 py-10 text-center text-gray-400">
                    <div class="text-4xl mb-3">💬</div>
                    <p class="font-medium">No chats yet</p>
                    <a href="{{ route('user.chats.index') }}" class="mt-3 inline-block text-indigo-600 text-sm hover:underline">Start your first chat →</a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('user.chats.index') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-indigo-700 transition shadow">
                💬 My Chats
            </a>
            <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-medium hover:bg-gray-50 transition shadow">
                🚀 Manage Subscription
            </a>
        </div>

    </div>
</div>
@endsection

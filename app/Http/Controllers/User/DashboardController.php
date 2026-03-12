<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $stats = [
            'total_chats'     => $user->chats()->count(),
            'total_messages'  => $user->messages()->where('is_auto_reply', false)->count(),
            'unread_messages' => $user->chats()
                ->withCount(['messages as unread_count' => fn ($q) => $q->whereNull('read_at')->where('user_id', '!=', $user->id)])
                ->get()->sum('unread_count'),
        ];

        $recentChats = $user->chats()->with(['messages' => fn ($q) => $q->latest()->limit(1)])->latest()->limit(5)->get();

        return view('user.dashboard', compact('stats', 'recentChats'));
    }
}

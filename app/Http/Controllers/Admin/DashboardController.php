<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users'     => User::count(),
            'total_chats'     => Chat::count(),
            'total_messages'  => Message::count(),
            'active_chats'    => Chat::where('is_active', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

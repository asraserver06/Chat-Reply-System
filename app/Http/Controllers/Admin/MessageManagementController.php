<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Repositories\ChatRepository;
use Illuminate\View\View;

class MessageManagementController extends Controller
{
    public function __construct(
        private readonly ChatRepository $chatRepository
    ) {}

    public function index(): View
    {
        $chats = $this->chatRepository->all(20);

        return view('admin.messages.index', compact('chats'));
    }

    public function show(Chat $chat): View
    {
        $chat->load(['user', 'messages.user']);

        return view('admin.messages.show', compact('chat'));
    }
}

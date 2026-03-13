<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Services\ChatService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function __construct(
        private readonly ChatService $chatService
    ) {}

    /** List all user's chat threads */
    public function index(): View
    {
        $chats = $this->chatService->userChats(auth()->user());
        return view('user.chats.index', compact('chats'));
    }

    /** Show a specific chat thread */
    public function show(Chat $chat): View
    {
        $this->authorize('view', $chat);

        $chat->load(['messages.user']);

        // Mark all messages as read
        $chat->messages()
            ->whereNull('read_at')
            ->where('user_id', '!=', auth()->id())
            ->update(['read_at' => now()]);

        return view('user.chats.show', compact('chat'));
    }

    /** Create a new chat thread */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Chat::class);

        $request->validate(['title' => 'required|string|max:100']);

        $chat = $this->chatService->openChat(auth()->user(), $request->title);

        return redirect()->route('user.chats.show', $chat)->with('success', 'Chat created!');
    }

    /** Send a message inside a chat thread */
    public function sendMessage(Request $request, Chat $chat): RedirectResponse
    {
        $this->authorize('view', $chat);

        $request->validate(['body' => 'required|string|max:5000']);

        $user = auth()->user();

        // Check message limit based on subscription plan
        if ($user->hasReachedMessageLimit()) {
            return back()->with('error', 'You have reached your plan\'s message limit. Please upgrade.');
        }

        $this->chatService->sendMessage($chat, $user, $request->body);

        return back()->with('success', 'Message sent!');
    }

    /** Delete a chat thread (and all its messages) */
    public function destroy(Chat $chat): RedirectResponse
    {
        $this->authorize('delete', $chat);
        $chat->forceDelete(); // permanent removal; booted() cascades to messages
        return redirect()->route('user.chats.index')->with('success', 'Chat deleted.');
    }

    /** Delete a single message (own, non-auto-reply only) */
    public function deleteMessage(Message $message): RedirectResponse
    {
        // Only the message owner can delete their own messages
        if ($message->user_id !== auth()->id()) {
            abort(403);
        }

        $chatId = $message->chat_id;
        $message->forceDelete();

        return redirect()->route('user.chats.show', $chatId)
            ->with('success', 'Message deleted.');
    }
}

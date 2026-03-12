<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(
        private readonly ChatService $chatService
    ) {}

    /** GET /api/chats */
    public function index(): JsonResponse
    {
        $chats = $this->chatService->userChats(auth()->user());
        return response()->json($chats);
    }

    /** POST /api/chats */
    public function store(Request $request): JsonResponse
    {
        $request->validate(['title' => 'required|string|max:100']);

        $chat = $this->chatService->openChat(auth()->user(), $request->title);

        return response()->json($chat, 201);
    }

    /** GET /api/chats/{chat} */
    public function show(Chat $chat): JsonResponse
    {
        $this->authorize('view', $chat);

        $chat->load('messages.user');

        return response()->json($chat);
    }

    /** DELETE /api/chats/{chat} */
    public function destroy(Chat $chat): JsonResponse
    {
        $this->authorize('delete', $chat);

        $chat->delete();

        return response()->json(['message' => 'Chat deleted.']);
    }
}

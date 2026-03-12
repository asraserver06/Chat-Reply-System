<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(
        private readonly ChatService $chatService
    ) {}

    /** GET /api/chats/{chat}/messages */
    public function index(Chat $chat): JsonResponse
    {
        $this->authorize('view', $chat);

        $messages = $chat->messages()->with('user')->oldest()->paginate(30);

        return response()->json($messages);
    }

    /** POST /api/chats/{chat}/messages */
    public function store(Request $request, Chat $chat): JsonResponse
    {
        $this->authorize('view', $chat);

        $request->validate(['body' => 'required|string|max:5000']);

        $user = auth()->user();

        if ($user->hasReachedMessageLimit()) {
            return response()->json([
                'message' => 'Message limit reached. Please upgrade your plan.',
            ], 422);
        }

        $message = $this->chatService->sendMessage($chat, $user, $request->body);

        return response()->json($message->load('user'), 201);
    }

    /** DELETE /api/messages/{message} */
    public function destroy(Message $message): JsonResponse
    {
        $this->authorize('delete', $message->chat);

        $message->delete();

        return response()->json(['message' => 'Message deleted.']);
    }
}

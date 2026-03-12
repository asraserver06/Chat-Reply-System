<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /** GET /api/user – authenticated user's profile */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user()->load('roles');

        return response()->json([
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'roles'        => $user->roles->pluck('name'),
            'plan'         => $user->currentPlanSlug(),
            'created_at'   => $user->created_at,
        ]);
    }

    /** GET /api/user/subscription */
    public function subscription(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'plan'         => $user->currentPlanSlug(),
            'subscription' => $user->subscription('default'),
        ]);
    }

    /** GET /api/user/notifications */
    public function notifications(Request $request): JsonResponse
    {
        $notifications = $request->user()
            ->unreadNotifications()
            ->latest()
            ->limit(20)
            ->get();

        return response()->json($notifications);
    }

    /** POST /api/user/notifications/read-all */
    public function markAllRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['message' => 'All notifications marked as read.']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        return response()->json($notifications);
    }

    public function markAsRead()
    {
        $user = auth()->user();

        // Atualiza todas as notificações não lidas do usuário para lidas
        Notification::where('user_id', $user->id)
                    ->where('read', false)
                    ->update(['read' => true]);

        return response()->json(['success' => true]);
    }

}
